<?php
/**
 * Controller pour la gestion des routes de l'application
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Controller\Admin
 */

namespace App\Controller\Admin;

use App\Controller\AppController;
use App\Repository\Admin\RouteRepository;
use App\Service\Admin\System\OptionService;
use App\Service\Admin\System\RouteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/admin/route', name: 'admin_route_')]
class RouteController extends AppController
{
    const SESSION_KEY_FILTER = 'session_route_filter';

    /**
     * Point d'entrée gestion des routes
     * @return Response
     */
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $breadcrumb = [
            $this->translator->trans('admin_dashboard#Dashboard') => 'admin_dashboard_index',
            $this->translator->trans('admin_route#Gestion des routes') => '',
        ];

        $fieldSearch = [
            'route' => $this->translator->trans("admin_route#Route"),
            'module' => $this->translator->trans("admin_route#Module"),
        ];

        return $this->render('admin/route/index.html.twig', [
            'breadcrumb' => $breadcrumb,
            'fieldSearch' => $fieldSearch
        ]);
    }

    /**
     * Mise à jour des routes
     * @param RouteService $routeService
     * @return JsonResponse
     */
    #[Route('/ajax/update', name: 'ajax_update_route')]
    public function updateRoute(RouteService $routeService): JsonResponse
    {
        $routeService->updateRoutes();
        return $this->json(['success' => true]);
    }

    /**
     * Liste des routes de l'application
     * @param int $page
     * @return Response
     */
    #[Route('/ajax/listing/{page}', name: 'ajax_listing_route')]
    public function listingRoute(int $page = 1): Response
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


        /** @var RouteRepository $routeRepo */
        $routeRepo = $this->getDoctrine()->getRepository(\App\Entity\Admin\Route::class);
        $listeRoutes = $routeRepo->listeRoutePaginate($page, $limit, $filter);
        $nbRoutesDepreciate = $routeRepo->findBy(['is_depreciate' => 1]);

        return $this->render('admin/route/ajax_listing.html.twig', [
            'listeRoutes' => $listeRoutes,
            'page' => $page,
            'limit' => $limit,
            'route' => 'admin_route_ajax_listing_route',
            'nbRoutesDepreciate' => count($nbRoutesDepreciate),
        ]);
    }
}
