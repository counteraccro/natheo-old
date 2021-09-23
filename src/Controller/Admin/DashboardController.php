<?php

namespace App\Controller\Admin;

use App\Controller\AppController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/dashboard', name: 'admin_dashboard_')]
class DashboardController extends AppController
{
    #[Route('/index', name: 'index')]
    public function index(): Response
    {
        return $this->render('admin/dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
}
