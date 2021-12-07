<?php
/**
 * Controller pour la gestion des pages l'application
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Controller\Admin
 */
namespace App\Controller\Admin;

use App\Entity\Admin\Page\Page;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/admin/role', name: 'admin_page_')]
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
     * @param int $page
     * @return Response
     */
    #[Route('/add/', name: 'add')]
    #[Route('/edit/{id}', name: 'edit')]
    public function createUpdate(Page $page = null): Response
    {

        $breadcrumb = [
            $this->translator->trans('admin_dashboard#Dashboard') => 'admin_dashboard_index',
            $this->translator->trans('admin_page#Gestion des pages') => 'admin_page_index',
        ];

        if($page == null)
        {
            $page = new Page();
            $title = $this->translator->trans('admin_page#Créer une page');
            $breadcrumb[$title] = '';
            //$flashMsg = $this->translator->trans('admin_page#Page créée avec succès');
        }
        else {

            $title = $this->translator->trans('admin_page#Edition de la page ') . '#' . $page->getId();
            $breadcrumb[$title] = '';
            //$flashMsg = $this->translator->trans('admin_page#Page édité avec succès');
        }

        return $this->render('admin/page/create-update.html.twig', [
            'breadcrumb' => $breadcrumb,
            'title' => $title,
            'page' => $page
        ]);
    }
}
