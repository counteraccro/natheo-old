<?php
/**
 * Controller pour la gestion des thèmes
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Controller\Admin
 */
namespace App\Controller\Admin;

use App\Controller\AppController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/theme', name: 'admin_theme_')]
class ThemeController extends AppController
{
    #[Route('/index', name: 'index')]
    public function index(): Response
    {
        $breadcrumb = [
            $this->translator->trans('admin_dashboard#Dashboard') => 'admin_dashboard_index',
            $this->translator->trans('admin_theme#Gestion des thèmes') => '',
        ];

        return $this->render('admin/theme/index.html.twig', [
            'breadcrumb' => $breadcrumb,
        ]);
    }
}
