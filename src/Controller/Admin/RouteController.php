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
    public function listing(int $page = 1): Response
    {
        $limit = $this->getOptionElementParPage();
        $filter = $this->getCriteriaGeneriqueSearch(self::SESSION_KEY_FILTER);

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

    /**
     * Permet de supprimer une route
     * @param \App\Entity\Admin\Route $route
     * @return JsonResponse
     */
    #[Route('/ajax/delete/{id}', name: 'ajax_delete_route')]
    public function delete(\App\Entity\Admin\Route $route): JsonResponse
    {
        $this->getDoctrine()->getManager()->remove($route);
        $this->getDoctrine()->getManager()->flush();
        return $this->json(['success' => true]);
    }

    /**
     * Permet de purger l'ensemble des routes défini comme obsolètes
     * @return JsonResponse
     */
    #[Route('/ajax/purge/', name: 'ajax_purge_route')]
    public function purge(): JsonResponse
    {
        $routeRepo = $this->getDoctrine()->getRepository(\App\Entity\Admin\Route::class);
        $listeRouteDepreciate = $routeRepo->findBy(['is_depreciate' => 1]);

        foreach($listeRouteDepreciate as $route)
        {
            $this->getDoctrine()->getManager()->remove($route);
        }
        $this->getDoctrine()->getManager()->flush();

        return $this->json(['success' => true]);
    }
}
