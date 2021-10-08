<?php
/**
 * Controller pour la gestion des roles l'application
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Controller\Admin
 */
namespace App\Controller\Admin;

use App\Controller\AppController;
use App\Entity\Admin\Role;
use App\Repository\Admin\RoleRepository;
use App\Service\Admin\System\OptionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/role', name: 'admin_role_')]
class RoleController extends AppController
{
    const SESSION_KEY_FILTER = 'session_role_filter';

    /**
     * Point d'entrée pour les roles
     * @return Response
     */
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $breadcrumb = [
            $this->translator->trans('admin_dashboard#Dashboard') => 'admin_dashboard_index',
            $this->translator->trans('admin_role#Gestion des rôles') => '',
        ];

        $fieldSearch = [
            'name' => $this->translator->trans("admin_role#Nom"),
            'label' => $this->translator->trans("admin_role#Label"),
            'shortLabel' => $this->translator->trans("admin_role#Label court"),
        ];

        return $this->render('admin/role/index.html.twig', [
            'breadcrumb' => $breadcrumb,
            'fieldSearch' => $fieldSearch
        ]);
    }

    /**
     * Permet de lister les roles
     * @param int $page
     */
    #[Route('/ajax/listing/{page}', name: 'ajax_listing_role')]
    public function listing(int $page = 1): Response
    {
        $limit = $this->optionService->getOptionByKey(OptionService::GO_ADM_GLOBAL_ELEMENT_PAR_PAGE, OptionService::GO_ADM_GLOBAL_ELEMENT_PAR_PAGE_DEFAULT_VALUE, true);
        $filter = $this->request->getCurrentRequest()->get('search_data', []);

        if ($page == 1 && $filter != null) {

            if($filter['field'] == 'reset')
            {
                $filter = null;
            }

            $this->session->set(self::SESSION_KEY_FILTER, $filter);
        } else {
            $filter = $this->session->get(self::SESSION_KEY_FILTER);
        }


        /** @var RoleRepository $routeRepo */
        $roleRepo = $this->getDoctrine()->getRepository(Role::class);
        $listeRoles = $roleRepo->listeRolePaginate($page, $limit, $filter);

        return $this->render('admin/role/ajax_listing.html.twig', [
            'listeRoles' => $listeRoles,
            'page' => $page,
            'limit' => $limit,
            'route' => 'admin_role_ajax_listing_role',
        ]);
    }
}
