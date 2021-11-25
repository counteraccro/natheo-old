<?php
/**
 * Controller qui va gérer les tags dans l'administration
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Controller\Admin\Module;
 */

namespace App\Controller\Admin\Modules;

use App\Controller\Admin\AppAdminController;
use App\Entity\Module\Tag;
use App\Form\Admin\Module\TagType;
use App\Repository\Module\TagRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/tag', name: 'admin_tag_')]
class TagController extends AppAdminController
{
    const SESSION_KEY_FILTER = 'session_tag_filter';
    const SESSION_KEY_PAGE = 'session_tag_page';

    /**
     * Point d'entrée de la gestion des tags
     * @param int $page
     * @return Response
     */
    #[Route('/index/{page}', name: 'index')]
    public function index(int $page = 1): Response
    {
        $breadcrumb = [
            $this->translator->trans('admin_dashboard#Dashboard') => 'admin_dashboard_index',
            $this->translator->trans('admin_tag#Gestion des tags') => '',
        ];

        $fieldSearch = [
            'name' => $this->translator->trans("admin_tag#Nom"),
        ];

        return $this->render('admin/modules/tag/index.html.twig', [
            'breadcrumb' => $breadcrumb,
            'fieldSearch' => $fieldSearch,
            'page' => $page
        ]);
    }

    /**
     * Permet de lister les users
     * @param int $page
     */
    #[Route('/ajax/listing/{page}', name: 'ajax_listing')]
    public function listing(int $page = 1): Response
    {

        $this->setPageInSession(self::SESSION_KEY_PAGE, $page);
        $limit = $this->optionService->getOptionElementParPage();

        $filter = $this->getCriteriaGeneriqueSearch(self::SESSION_KEY_FILTER);

        /** @var TagRepository $routeRepo */
        $tagRepo = $this->getDoctrine()->getRepository(Tag::class);
        $listeTags = $tagRepo->listeTagPaginate($page, $limit, $filter);

        return $this->render('admin/modules/tag/ajax/ajax-listing.html.twig', [
            'listeTags' => $listeTags,
            'page' => $page,
            'limit' => $limit,
            'route' => 'admin_tag_ajax_listing',
        ]);
    }

    /**
     * Permet de créer / éditer un tag
     * @param Tag|null $tag
     * @return RedirectResponse|Response
     */
    #[Route('/add/', name: 'add')]
    #[Route('/edit/{id}', name: 'edit')]
    public function createUpdate(Tag $tag = null): RedirectResponse|Response
    {
        $breadcrumb = [
            $this->translator->trans('admin_dashboard#Dashboard') => 'admin_dashboard_index',
            $this->translator->trans('admin_tag#Gestion des tags') => ['admin_tag_index', ['page' => $this->getPageInSession(self::SESSION_KEY_PAGE)]]
        ];

        if ($tag == null) {
            $action = 'add';
            $tag = new Tag();
            $title = $this->translator->trans('admin_tag#Ajouter un tag');
            $breadcrumb[$title] = '';
            $flashMsg = $this->translator->trans('admin_tag#Tag Crée avec succès');
        } else {
            $action = 'edit';
            $title = $this->translator->trans('admin_tag#Edition du tag ') . '#' . $tag->getId();
            $breadcrumb[$title] = '';
            $flashMsg = $this->translator->trans('admin_tag#Tag édité avec succès');
        }

        $form = $this->createForm(TagType::class, $tag);

        $form->handleRequest($this->request->getCurrentRequest());
        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->persist($tag);
            $this->getDoctrine()->getManager()->flush();

            $param = [];
            $this->addFlash('success', $flashMsg);
            if ($action == 'edit') {
                $param = ['page' => $this->getPageInSession(self::SESSION_KEY_PAGE)];
            }
            return $this->redirectToRoute('admin_tag_index', $param);
        }

        return $this->render('admin/modules/tag/create-update.html.twig', [
            'breadcrumb' => $breadcrumb,
            'form' => $form->createView(),
            'title' => $title,
            'tag' => $tag,
            'action' => $action,
        ]);
    }

    /**
     * Permet de supprimer un tag
     * @param Tag $tag
     * @return RedirectResponse
     */
    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Tag $tag): RedirectResponse
    {
        $flashMsg = $this->translator->trans('admin_tag#Tag supprimé avec succès');
        $this->getDoctrine()->getManager()->remove($tag);
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash('success', $flashMsg);
        return $this->redirectToRoute('admin_tag_index');
    }
}
