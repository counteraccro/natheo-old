<?php
/**
 * Controller qui va génrer les options
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Controller\Admin;
 */
namespace App\Controller\Admin;

use App\Controller\AppController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/option', name: 'admin_option_')]
class OptionController extends AppController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $breadcrumb = [
            $this->translator->trans('admin_dashboard#Dashboard') => 'admin_dashboard_index',
            $this->translator->trans('admin_global_option#Options globales') => '',
        ];


        return $this->render('admin/option/index.html.twig', [
            'breadcrumb' => $breadcrumb,
        ]);
    }
}
