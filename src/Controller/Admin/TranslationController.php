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
use App\Entity\Admin\TranslationLabel;
use App\Repository\Admin\TranslationKeyRepository;
use App\Service\Admin\System\DataSystemService;
use App\Service\Admin\System\OptionService;
use App\Service\Admin\System\TranslationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/translate', name: 'admin_translation_')]
class TranslationController extends AppController
{
    const SESSION_KEY_FILTER = 'session_translation_filter';

    #[Route('/', name: 'index')]
    public function index(): Response
    {

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
        $limit = $this->optionService->getOptionByKey(OptionService::GO_ADM_GLOBAL_ELEMENT_PAR_PAGE, OptionService::GO_ADM_GLOBAL_ELEMENT_PAR_PAGE_DEFAULT_VALUE,true);
        $tmpfilter = $this->request->getCurrentRequest()->get('translation_filter', null);

        if ($page == 1 && $tmpfilter != null) {
            $filter = [];

            foreach ($tmpfilter as $filtre) {
                $tmp = explode('_', $filtre['name']);
                if (count($tmp) > 1) {
                    $filter['language'][] = $tmp[1];
                } else {
                    $filter[$filtre['name']] = $filtre['value'];
                }
            }
            $this->session->set(self::SESSION_KEY_FILTER, $filter);
        } else {
            $filter = $this->session->get(self::SESSION_KEY_FILTER);
        }

        /** @var TranslationKeyRepository $translationKeyRepo */
        $translationKeyRepo = $this->getDoctrine()->getRepository(TranslationKey::class);
        $listeTranslation = $translationKeyRepo->listeRoutePaginate($page, $limit, $filter);

        return $this->render('admin/translation/ajax_listing.html.twig', [
            'listeTranslation' => $listeTranslation,
            'page' => $page,
            'limit' => $limit,
            'route' => 'admin_translation_ajax_listing_translation',
            'languages' => $filter['language'],
            'translationModules' => $this->translationService->getTranslationModule()
        ]);
    }

    /**
     * Permet de régénérer les traductions depuis le code
     */
    #[Route('/ajax/reset-translation', name: 'ajax_reset_translation')]
    public function ResetAllTranslation(TranslationService $translationService): JsonResponse
    {
        $translationService->generateTranslationByCommande();
        $translationService->updateTranslateFromYamlFileToBDD();
        $translationService->updateTranslateFromBDDtoYamlFile();
        return $this->json(['success' => true]);
    }

    /**
     * Permet de régénérer les traductions depuis la base de données
     */
    #[Route('/ajax/reload-translation', name: 'ajax_reload_translation')]
    public function ReloadAllTranslation(TranslationService $translationService): JsonResponse
    {
        $translationService->updateTranslateFromBDDtoYamlFile();
        return $this->json(['success' => true]);
    }

    /**
     * Met à jour un translationLabel
     * @param TranslationLabel $translationLabel
     */
    #[Route('/ajax/update-label/{id}', name: 'ajax_update_translation')]
    public function updateLabel(TranslationLabel $translationLabel): JsonResponse
    {
        $label = $this->request->getCurrentRequest()->get('label');

        if ($translationLabel->getLabel() != $label) {
            $translationLabel->setLabel($label);
            $this->getDoctrine()->getManager()->persist($translationLabel);
            $this->getDoctrine()->getManager()->flush();
            $this->dataSystemService->update(DataSystemService::DATA_SYSTEM_TRANSLATION_GENERATE, 1, DataSystemService::ADDITION);
        }
        return $this->json(['success' => true]);
    }

    /**
     * Permet de vérifier si on doit régénérer les translations ou non
     */
    #[Route('/ajax/check-reload-translation/', name: 'ajax_check_reload_translation')]
    public function checkReloadTranslation(): JsonResponse
    {
        $dataSystem = $this->dataSystemService->getDataSystem(DataSystemService::DATA_SYSTEM_TRANSLATION_GENERATE);

        if ($dataSystem->getValue() == "" || $dataSystem->getValue() <= 0) {
            $msg = '<div class="text-success"><i class="fa fa-info-circle"></i> <i>' . $this->translator->trans('admin_translation#Les traductions sont à jour') . '</i></div>';
        } else {
            $msg = '<div class="text-warning"><i class="fa fa-exclamation-circle"></i> <i>' . $this->translator->trans('admin_translation#Les traductions ne sont plus à jour vis à vis de la base de données, Vous devez régénérer les traductions depuis la base de données') . '</i></div>';
        }

        return $this->json(['msg' => $msg]);
    }
}
