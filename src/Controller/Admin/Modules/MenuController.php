<?php
/**
 * Controller qui va gérer les menus dans l'administration pour le front
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Controller\Admin\Module;
 */
namespace App\Controller\Admin\Modules;

use App\Controller\Admin\AppAdminController;
use App\Entity\Modules\Menu\Menu;
use App\Form\Modules\Menu\MenuType;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

    /**
     * Permet de créer / éditer un tag
     * @param Menu|null $menu
     * @return RedirectResponse|Response
     */
    #[Route('/add/', name: 'add')]
    #[Route('/edit/{id}', name: 'edit')]
    public function createUpdate(Menu $menu = null): RedirectResponse|Response
    {
        $breadcrumb = [
            $this->translator->trans('admin_dashboard#Dashboard') => 'admin_dashboard_index',
            $this->translator->trans('admin_system#Modules') => '',
            $this->translator->trans('admin_menu#Gestion des menu') => ['admin_menu_index', ['page' => $this->getPageInSession(self::SESSION_KEY_PAGE)]]
        ];

        if ($menu == null) {
            $action = 'add';
            $menu = new Menu();
            $title = $this->translator->trans('admin_menu#Ajouter un menu');
            $breadcrumb[$title] = '';
            $flashMsg = $this->translator->trans('admin_menu#Menu Créé avec succès');
        } else {
            $action = 'edit';
            $title = $this->translator->trans('admin_menu#Edition du menu ') . '#' . $menu->getId();
            $breadcrumb[$title] = '';
            $flashMsg = $this->translator->trans('admin_menu#Menu édité avec succès');
        }

        $form = $this->createForm(MenuType::class, $menu);

        $form->handleRequest($this->request->getCurrentRequest());
        if ($form->isSubmitted() && $form->isValid()) {

            //$this->doctrine->getManager()->persist($tag);
            //$this->doctrine->getManager()->flush();

            $param = [];
            $this->addFlash('success', $flashMsg);
            if ($action == 'edit') {
                $param = ['page' => $this->getPageInSession(self::SESSION_KEY_PAGE)];
            }
            //return $this->redirectToRoute('admin_tag_index', $param);
        }

        return $this->render('admin/modules/menu/create-update.html.twig', [
            'breadcrumb' => $breadcrumb,
            'form' => $form->createView(),
            'title' => $title,
            'menu' => $menu,
            'action' => $action,
        ]);
    }
}
