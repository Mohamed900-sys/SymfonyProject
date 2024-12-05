<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Abonnement;
use App\Entity\UserAbonnement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class UserAbonnementController extends AbstractController
{
    #[Route('/abonnement/{userId}/{abonnementId}/{subscriptionLength}', name: 'subscribe')]
    public function subscribe(int $userId,int $abonnementId, int $subscriptionLength, EntityManagerInterface $em, Request $request)
    {
        // Get the user entity (assuming the User entity exists and is valid)
        $user = $em->getRepository(User::class)->find($userId);
        $abonnement = $em->getRepository(Abonnement::class)->find($abonnementId);


        if (!$user) {
            return $this->redirectToRoute('error'); // Handle invalid user
        }

        // Get the current date
        $startDate = new \DateTime();  // Current date

        // Add the subscription length (in months) to the current date
        $endDate = clone $startDate;  // Clone the start date to avoid modifying it directly
        $endDate->modify('+' . $subscriptionLength . ' months');  // Add the subscription length (in months)

        // Create a new Subscription entity
        $subscription = new UserAbonnement();
        $subscription->setUser($user);
        $subscription->setAbonnement($abonnement);
        $subscription->setStartDate($startDate);
        $subscription->setEndDate($endDate);

        // Persist the subscription
        $em->persist($subscription);
        $em->flush();

        // Redirect or render a response
        // return $this->redirectToRoute('subscription_success');
        return $this->redirectToRoute('app_abonnement_index');

        //return new Response(("<h1>DONE</h1>"));
        
    }

}
