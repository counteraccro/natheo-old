<?php
/**
 * Controller qui va génrer le dashboard
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Controller\Admin;
 */
namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/dashboard', name: 'admin_dashboard_')]
class DashboardController extends AppAdminController
{
    #[Route('/index', name: 'index')]
    public function index(): Response
    {
        return $this->render('admin/dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    /**
     * A supprimer à terme
     * @return Response
     */
    #[Route('/todo', name: 'todo')]
    public function todo() {

        return $this->render('admin/dashboard/todo.html.twig', [
        ]);
    }
}
