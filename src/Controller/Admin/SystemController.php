<?php
/**
 * Controller pour la gestion du system
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Controller\Admin
 */
namespace App\Controller\Admin;

use App\Controller\AppController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/system', name: 'admin_system_')]
class SystemController extends AppController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $breadcrumb = [
            $this->translator->trans('admin_dashboard#Dashboard') => 'admin_dashboard_index',
            $this->translator->trans('admin_system#Debug System') => '',
        ];

        return $this->render('admin/system/index.html.twig', [
            'breadcrumb' => $breadcrumb,
            'app_env' => $this->kernel->getEnvironment()
        ]);
    }
}
