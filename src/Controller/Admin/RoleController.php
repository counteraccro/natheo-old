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
use App\Entity\Admin\RouteRight;
use App\Form\Admin\RoleType;
use App\Repository\Admin\RoleRepository;
use App\Repository\Admin\RouteRepository;
use App\Service\Admin\RoleService;
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
    #[Route('/ajax/listing/{page}', name: 'ajax_listing')]
    public function listing(int $page = 1): Response
    {

        $limit = $this->getOptionElementParPage();
        $filter = $this->getCriteriaGeneriqueSearch(self::SESSION_KEY_FILTER);

        /** @var RoleRepository $routeRepo */
        $roleRepo = $this->getDoctrine()->getRepository(Role::class);
        $listeRoles = $roleRepo->listeRolePaginate($page, $limit, $filter);

        return $this->render('admin/role/ajax-listing.html.twig', [
            'listeRoles' => $listeRoles,
            'page' => $page,
            'limit' => $limit,
            'route' => 'admin_role_ajax_listing',
        ]);
    }

    /**
     * Permet d'éditer  créer un nouveau role
     * @param Role|null $role
     * @return Response
     */
    #[Route('/add/', name: 'add')]
    #[Route('/edit/{id}', name: 'edit')]
    public function createUpdate(Role $role = null): Response
    {
        $breadcrumb = [
            $this->translator->trans('admin_dashboard#Dashboard') => 'admin_dashboard_index',
            $this->translator->trans('admin_role#Gestion des rôles') => 'admin_role_index',
        ];

        /** @var RouteRepository $RouteRepo */
        $RouteRepo = $this->getDoctrine()->getRepository(\App\Entity\Admin\Route::class);
        $listeRoutes = $RouteRepo->getListeOrderBy();

        if($role == null)
        {
            $role = new Role();
            $title = $this->translator->trans('admin_role#Créer un rôle');
            $breadcrumb[$title] = '';
            $flashMsg = $this->translator->trans('admin_role#Rôle créé avec succès');
        }
        else {

            // Si on tente d'éditer le role route, on rejette la demande
            if($role->getName() == RoleService::ROOT_NAME)
            {
                return $this->redirectToRoute('admin_role_index');
            }

            $title = $this->translator->trans('admin_role#Edition du rôle ') . '#' . $role->getId();
            $breadcrumb[$title] = '';
            $flashMsg = $this->translator->trans('admin_role#Rôle édité avec succès');
        }
        $form = $this->createForm(RoleType::class, $role);

        $form->handleRequest($this->request->getCurrentRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Role $role */
            $role = $form->getData();

            $role->getRouteRights()->clear();

            $listeIdRoutes = explode('-', $this->request->getCurrentRequest()->request->all()['role']['routes']);
            if(!empty($listeIdRoutes))
            {
                foreach ($listeIdRoutes as $id) {
                    $routeRight = new RouteRight();
                    $routeRight->setRoute($RouteRepo->findOneBy(['id' => $id]));
                    $routeRight->setRole($role);
                    $routeRight->setCanDelete(true)->setCanEdit(true)->setCanRead(true);
                    $this->getDoctrine()->getManager()->persist($routeRight);
                    $role->addRouteRight($routeRight);
                }
            }

            $this->getDoctrine()->getManager()->persist($role);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', $flashMsg);
            return $this->redirectToRoute('admin_role_index');
        }


        return $this->render('admin/role/create-update.html.twig', [
            'breadcrumb' => $breadcrumb,
            'form' => $form->createView(),
            'listeRoutes' => $listeRoutes,
            'title' => $title,
            'role' => $role
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Role $role)
    {
        $flashMsg = $this->translator->trans('admin_role#Rôle supprimé avec succès');
        $this->getDoctrine()->getManager()->remove($role);
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash('success', $flashMsg);
        return $this->redirectToRoute('admin_role_index');
    }
}
