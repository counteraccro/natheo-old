<?php

namespace App\Controller\Admin;

use App\Controller\AppController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/route', name: 'admin_routing_')]
class RouteController extends AppController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $breadcrumb = [
            $this->translator->trans('Dashboard') => 'admin_dashboard_index',
            $this->translator->trans('Gestion des routes') => '',
        ];

        return $this->render('admin/route/index.html.twig', [
            'breadcrumb' => $breadcrumb,
        ]);
    }
}
