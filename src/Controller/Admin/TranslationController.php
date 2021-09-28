<?php
/**
 * Controller qui va gérer les traductions
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Controller\Admin;
 */
namespace App\Controller\Admin;

use App\Controller\AppController;
use App\Entity\Admin\TranslationKey;
use App\Repository\Admin\TranslationKeyRepository;
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

        //$translationService->updateTranslateFromBDDtoYamlFile();

        $breadcrumb = [
            $this->translator->trans('admin_dashboard#Dashboard') => 'admin_dashboard_index',
            $this->translator->trans('admin_translation#Gestion des traductions') => '',
        ];

        return $this->render('admin/translation/index.html.twig', [
            'breadcrumb' => $breadcrumb,
        ]);
    }

    /**
     * Liste des traductions de l'application
     * @param int $page
     * @return Response
     */
    #[Route('/ajax/listing/{page}', name: 'ajax_listing_translation')]
    public function listingRoute(int $page = 1): Response
    {
        $limit = 6;

        /** @var TranslationKeyRepository $translationKeyRepo */
        $translationKeyRepo = $this->getDoctrine()->getRepository(TranslationKey::class);
        $listeTranslation = $translationKeyRepo->listeRoutePaginate($page, $limit);

        return $this->render('admin/translation/ajax_listing.html.twig', [
            'listeTranslation' => $listeTranslation,
            'page' => $page,
            'limit' => $limit,
            'route' => 'admin_translation_ajax_listing_translation',
        ]);
    }
}
