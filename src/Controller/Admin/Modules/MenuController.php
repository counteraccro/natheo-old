<?php
/**
 * Controller qui va gérer les menus dans l'administration pour le front
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Controller\Admin\Module;
 */
namespace App\Controller\Admin\Modules;

use App\Controller\Admin\AppAdminController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/menu', name: 'admin_menu_')]
class MenuController extends AppAdminController
{
    const SESSION_KEY_FILTER = 'session_menu_filter';
    const SESSION_KEY_PAGE = 'session_menu_page';

    /**
     * Point d'entrée de la gestion des menus
     * @return Response
     */
    #[Route('/index/{page}', name: 'index')]
    public function index(int $page = 1): Response
    {

        $breadcrumb = [
            $this->translator->trans('admin_dashboard#Dashboard') => 'admin_dashboard_index',
            $this->translator->trans('admin_system#Modules') => '',
            $this->translator->trans('admin_menu#Gestion des menus') => '',
        ];

        $fieldSearch = [
            'name' => $this->translator->trans("admin_tag#Nom"),
        ];

        return $this->render('admin/modules/menu/index.html.twig', [
            'breadcrumb' => $breadcrumb,
            'fieldSearch' => $fieldSearch,
            'page' => $page
        ]);
    }

    /**
     * Permet de lister les menus
     * @param int $page
     */
    #[Route('/ajax/listing/{page}', name: 'ajax_listing')]
    public function listing(int $page = 1): Response
    {

        /*$this->setPageInSession(self::SESSION_KEY_PAGE, $page);
        $limit = $this->optionService->getOptionElementParPage();

        $filter = $this->getCriteriaGeneriqueSearch(self::SESSION_KEY_FILTER);

        /** @var TagRepository $routeRepo
        $tagRepo = $this->doctrine->getRepository(Tag::class);
        $listeTags = $tagRepo->listeTagPaginate($page, $limit, $filter);

        return $this->render('admin/modules/tag/ajax/ajax-listing.html.twig', [
            'listeTags' => $listeTags,
            'page' => $page,
            'limit' => $limit,
            'route' => 'admin_tag_ajax_listing',
        ]);*/
    }
}
