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
use App\Service\Admin\System\RouteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/admin/route', name: 'admin_route_')]
class RouteController extends AppController
{

    /**
     * Point d'entrée gestion des routes
     * @return Response
     */
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $breadcrumb = [
            $this->translator->trans('Dashboard') => 'admin_dashboard_index',
            $this->translator->trans('Gestion des routes') => '',
        ];

        return $this->render('admin/route/index.html.twig', [
            'breadcrumb' => $breadcrumb,
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
        $limit = 6;

        /** @var RouteRepository $routeRepo */
        $routeRepo = $this->getDoctrine()->getRepository(\App\Entity\Admin\Route::class);
        $listeRoutes = $routeRepo->listeRoutePaginate($page, $limit);
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
