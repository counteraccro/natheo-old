<?php
/**
 * Controller pour la gestion des pages l'application
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Controller\Admin
 */

namespace App\Controller\Admin;

use App\Entity\Admin\Page\Page;
use App\Form\Admin\Page\PageType;
use App\Service\Admin\PageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/page', name: 'admin_page_')]
class PageController extends AppAdminController
{
    const SESSION_KEY_FILTER = 'session_role_filter';
    const SESSION_KEY_PAGE = 'session_role_page';

    /**
     * Point d'entrée pour les pages
     * @param int $page
     * @return Response
     */
    #[Route('/index/{page}', name: 'index')]
    public function index(int $page = 1): Response
    {
        $this->pageService->removeCurrentObjPage();
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
     * Permet de lister les pages
     * @param int $page
     * @return Response
     */
    #[Route('/ajax/listing/{page}', name: 'ajax_listing')]
    public function listing(int $page = 1): Response
    {
        /*$this->setPageInSession(self::SESSION_KEY_PAGE, $page);
        $limit = $this->optionService->getOptionElementParPage();
        $filter = $this->getCriteriaGeneriqueSearch(self::SESSION_KEY_FILTER);

        /** @var RoleRepository $routeRepo
        $roleRepo = $this->doctrine->getRepository(Role::class);
        $listeRoles = $roleRepo->listeRolePaginate($page, $limit, $filter);

        return $this->render('admin/role/ajax/ajax-listing.html.twig', [
            'listeRoles' => $listeRoles,
            'page' => $page,
            'limit' => $limit,
            'route' => 'admin_role_ajax_listing',
        ]);*/
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
            $url_form = $this->generateUrl('admin_page_ajax_add_page', ['language' => $currentLocale]);
        } else {
            $title = $this->translator->trans('admin_page#Edition de la page ') . '#' . $page->getId();
            $breadcrumb[$title] = '';
            $url_form = $this->generateUrl('admin_page_ajax_edit_page', ['id' => $page->getId(), 'language' => $currentLocale]);
        }

        return $this->render('admin/page/create-update.html.twig', [
            'breadcrumb' => $breadcrumb,
            'title' => $title,
            'url_form' => $url_form,
            'locales' => $locales,
            'currentLocale' => $currentLocale
        ]);
    }

    /**
     * Permet de créer / éditer une page
     * @param Page|null $page
     * @return Response
     */
    #[Route('/ajax/add/{language}', name: 'ajax_add_page')]
    #[Route('/ajax/edit/{id}/{language}', name: 'ajax_edit_page')]
    public function ajaxCreateUpdate(Page $page = null, string $language = 'fr'): Response
    {

        $page = $this->pageService->setCurrentObjPage($language, $page);
        $this->session->set(PageService::SESSION_KEY_CURRENT_LOCAl_PAGE, $language);

        /*if($page == null)
        {
            $page = new Page();
        }
        else {

        }*/

        $form = $this->createForm(PageType::class, $page);

        $form->handleRequest($this->request->getCurrentRequest());
        if ($form->isSubmitted() && $form->isValid()) {

            $page = $form->getData();
            var_dump($page);
        }

        return $this->render('admin/page/ajax/ajax-create-update.html.twig', [
            'page' => $page,
            'form' => $form->createView(),
            'language' => $language
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

    /**
     * Permet de charger la page courante pour générer la liste de choix de langue
     * @param string $language
     * @return Response
     */
    #[Route('/ajax/load-page-language', name: 'ajax_load_page_language')]
    public function loadPageLanguage() : Response
    {
        $page = $this->pageService->getCurrentObjPage();
        $language = $this->session->get(PageService::SESSION_KEY_CURRENT_LOCAl_PAGE);

        return $this->render('admin/page/ajax/ajax-load-page-language.html.twig', [
            'page' => $page,
            'currentLocale' => $language,
        ]);
    }
}
