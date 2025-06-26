<?php

namespace App\Controller\Admin\RegisterAndAuth;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboard extends AbstractController

{
    #[Route(path: '/admin/dashboard', name: 'admin_dashboard')]
    public function dashboard(): Response
    {
        return  $this->render(
            'admin/register_and_auth/admin_dashboard.html.twig'

        );
    }
}
