<?php
/**
 * Controller qui va gérer les traductions
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Controller\Admin;
 */
namespace App\Controller\Admin;

use App\Controller\AppController;
use App\Service\Admin\System\TranslationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/translate', name: 'admin_translation_')]
class TranslationController extends AppController
{
    #[Route('/', name: 'index')]
    public function index(TranslationService $translationService): Response
    {

        $translationService->updateTranslateFromYamlFileToBDD();

        $breadcrumb = [
            $this->translator->trans('admin_dashboard#Dashboard') => 'admin_dashboard_index',
            $this->translator->trans('admin_translation#Gestion des traductions') => '',
        ];

        return $this->render('admin/translation/index.html.twig', [
            'breadcrumb' => $breadcrumb,
        ]);
    }
}
