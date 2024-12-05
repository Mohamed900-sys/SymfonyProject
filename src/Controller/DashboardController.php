<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function adminDashboard(): Response
    {
        return $this->render('dashboard/admin.html.twig',["page" => "Profile"]);
    }

    #[Route('/coach/dashboard', name: 'coach_dashboard')]
    public function coachDashboard(): Response
    {
        return $this->render('dashboard/coach.html.twig',["page" => "Profile"]);
    }

    #[Route('/owner/dashboard', name: 'gym_owner_dashboard')]
    public function gymOwnerDashboard(): Response
    {
        return $this->render('dashboard/gym_owner.html.twig',["page" => "Profile"]);
    }

    #[Route('/user/dashboard', name: 'user_dashboard')]
    public function userDashboard(): Response
    {
        return $this->render('dashboard/user.html.twig',["page" => "Profile"]);
    }
}
