<?php
/**
 * Controller pour la gestion des pages l'application
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Controller\Admin
 */

namespace App\Controller\Admin;

use App\Entity\Admin\Page\Page;
use App\Entity\Admin\Page\PageTranslation;
use App\Form\Admin\Page\PageType;
use App\Service\Admin\PageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/page', name: 'admin_page_')]
class PageController extends AppAdminController
{
    const SESSION_KEY_FILTER = 'session_role_filter';

    /**
     * Point d'entrée pour les pages
     * @param int $page
     * @return Response
     */
    #[Route('/index/{page}', name: 'index')]
    public function index(int $page = 1): Response
    {
        $this->tagService->resetTmpTag();

        $breadcrumb = [
            $this->translator->trans('admin_dashboard#Dashboard') => 'admin_dashboard_index',
            $this->translator->trans('admin_page#Gestion des pages') => '',
        ];

        $fieldSearch = [
            'name' => $this->translator->trans("admin_role#Nom"),
            'label' => $this->translator->trans("admin_role#Label"),
            'shortLabel' => $this->translator->trans("admin_role#Label court"),
        ];

        return $this->render('admin/page/index.html.twig', [
            'breadcrumb' => $breadcrumb,
            'fieldSearch' => $fieldSearch,
            'page' => $page
        ]);
    }

    /**
     * Permet de créer / éditer une page
     * @param Page|null $page
     * @return Response
     */
    #[Route('/add/', name: 'add')]
    #[Route('/edit/{id}', name: 'edit')]
    public function createUpdate(Page $page = null): Response
    {

        $currentLocale = $this->request->getCurrentRequest()->getLocale();
        $locales = $this->translationService->getLocales();

        $breadcrumb = [
            $this->translator->trans('admin_dashboard#Dashboard') => 'admin_dashboard_index',
            $this->translator->trans('admin_page#Gestion des pages') => 'admin_page_index',
        ];

        if ($page == null) {
            $title = $this->translator->trans('admin_page#Créer une page');
            $breadcrumb[$title] = '';

            $page = new Page();

            $locales = $this->translationService->getLocales();
            foreach ($locales as $locale) {
                $pageTranslation = new PageTranslation();
                $pageTranslation->setLanguage($locale);
                $page->addPageTranslation($pageTranslation);
            }

        } else {
            $title = $this->translator->trans('admin_page#Edition de la page ') . '#' . $page->getId();
            $breadcrumb[$title] = '';
        }

        $form = $this->createForm(PageType::class, $page);

        $form->handleRequest($this->request->getCurrentRequest());
        if ($form->isSubmitted() && $form->isValid()) {

            $page = $form->getData();
            var_dump($page);
        }

        return $this->render('admin/page/create-update.html.twig', [
            'breadcrumb' => $breadcrumb,
            'title' => $title,
            'locales' => $locales,
            'currentLocal' => $currentLocale,
            'page' => $page,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de selectionner un template
     * @return Response
     */
    #[Route('/ajax/select-template', name: 'ajax_select_template')]
    public function selectTemplate(): Response
    {
        $tabTemplate = $this->pageService->getSelectedTemplate();

        return $this->render('admin/page/ajax/ajax-modal-select-template.html.twig', [
            'id' => $tabTemplate['id']
        ]);
    }

    /**
     * Permet de sauvegarder le template selectionné
     * @param int $id
     * @return Response
     */
    #[Route('/ajax/save-select-template/{id}', name: 'ajax_select_save_template')]
    public function saveSelectTemplate(int $id = 0): Response
    {
        $this->pageService->setSelectedTemplate($id);

        return $this->json(['success' => true]);
    }

    /**
     * Permet de charger le template selectionné pour l'édition / création d'une page
     * @return Response
     */
    #[Route('/ajax/load-template', name: 'ajax_load_template')]
    public function loadTemplate(): Response
    {
        $tabTemplate = $this->pageService->getSelectedTemplate();

        return $this->render('admin/page/ajax/ajax-template.html.twig', [
            'template' => $tabTemplate['base']
        ]);

    }
}
