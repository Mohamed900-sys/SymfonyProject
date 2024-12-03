<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginFormType;
use App\Form\RegisterFormType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/user')]
final class UserController extends AbstractController 
{
    /**
    * @ParamConverter("user", class="App\Entity\User")
    */

    #[Route(name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    // #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $user = new User();
    //     $form = $this->createForm(UserType::class, $user);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->persist($user);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('user/new.html.twig', [
    //         'user' => $user,
    //         'form' => $form,
    //     ]);
    // }

    //#[Route('/register', name: 'app_user_register', methods: ['GET', 'POST'])]
    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function register(EntityManagerInterface $manager,ManagerRegistry $entityManager, UserPasswordHasherInterface $passwordHasher,Request $request): Response
    {
        $user = new User();

        //create form 
        $form = $this->createForm(RegisterFormType::class,$user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // Check if the email already exists
            $userRepository = $entityManager->getRepository(User::class);

            $existingUser = $userRepository->findOneBy(['email' => $user->getEmail()]);
            if ($existingUser) {
                $this->addFlash('error', 'A user with this email already exists.');
                return $this->redirect($request->getUri());
            }
            //hash password 
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
            //save
            $manager->persist($user);
            $manager->flush();
            // return $this->redirect($request->getUri());
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);

        }



        return $this->render('user/register.html.twig', [
            "registerForm" => $form->createView(),
        ]);
    }

    #[Route('/login', name: 'app_user_login')]
    public function login(EntityManagerInterface $manager,
                        ManagerRegistry $entityManager,
                        UserPasswordHasherInterface $passwordHasher ,
                        SessionInterface $session,
                        Request $request): Response
    {
        $user = new User();

        //create form 
        $form = $this->createForm(LoginFormType::class,$user);

        $form->handleRequest($request);


        $repository = $entityManager->getRepository(User::class);
        
        //dd($_POST["login_form"]);
        if(isset($_POST["login_form"])){
            //save
            $email = $_POST["login_form"]["email"];
            $password = $_POST["login_form"]["password"];
            $userDb = $repository->findOneBy(['email' => $email]);

            if (!$userDb) {
                // Flash message for invalid email
                $this->addFlash('error', 'Invalid email or password.');
                return $this->redirectToRoute('app_user_login');
            }

            // Verify password
            if ($passwordHasher->isPasswordValid($userDb, $password)) {
                //dd($user_db);
                // Start a session
                $session->set('user_id', $userDb->getId());
                $session->set('user_type', $userDb->getRole());

                // Redirect based on user role
                switch ($userDb->getRole()) {
                    case 'admin':
                        return $this->redirectToRoute('admin_dashboard');
                    case 'coach':
                        return $this->redirectToRoute('coach_dashboard');
                    case 'gym_owner':
                        return $this->redirectToRoute('gym_owner_dashboard');
                    default:
                        return $this->redirectToRoute('user_dashboard');
                }

            // You can start a session or redirect to a user-specific dashboard
            return $this->redirectToRoute('user_dashboard');
            }else {
                // Flash message for invalid password
                $this->addFlash('error', 'Invalid email or password.');
            }
        

            //return $this->redirect($request->getUri());
        }



        return $this->render('user/login.html.twig', [
            "loginForm" => $form->createView(),
        ]);
    }
    #[Route('/logout', name: 'app_user_logout')]
    public function logout(SessionInterface $session): Response
    {
        $session->clear();
        return $this->redirectToRoute('app_user_login');
    }



    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
