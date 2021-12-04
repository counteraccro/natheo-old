<?php
/**
 * Controller qui va gérer les tags dans l'administration
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Controller\Admin\Module;
 */

namespace App\Controller\Admin\Modules;

use App\Controller\Admin\AppAdminController;
use App\Entity\Modules\Tag;
use App\Form\Modules\TagType;
use App\Repository\Modules\TagRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

#[Route('/admin/tag', name: 'admin_tag_')]
class TagController extends AppAdminController
{
    const SESSION_KEY_FILTER = 'session_tag_filter';
    const SESSION_KEY_PAGE = 'session_tag_page';
    const SESSION_KEY_TMP_TAG = 'session_tag_tmp_save';

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
            $this->translator->trans('admin_system#Modules') => '',
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
     * Permet de lister les tags
     * @param int $page
     */
    #[Route('/ajax/listing/{page}', name: 'ajax_listing')]
    public function listing(int $page = 1): Response
    {

        $this->setPageInSession(self::SESSION_KEY_PAGE, $page);
        $limit = $this->optionService->getOptionElementParPage();

        $filter = $this->getCriteriaGeneriqueSearch(self::SESSION_KEY_FILTER);

        /** @var TagRepository $routeRepo */
        $tagRepo = $this->doctrine->getRepository(Tag::class);
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

            $this->doctrine->getManager()->persist($tag);
            $this->doctrine->getManager()->flush();

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
        $this->doctrine->getManager()->remove($tag);
        $this->doctrine->getManager()->flush();
        $this->addFlash('success', $flashMsg);
        return $this->redirectToRoute('admin_tag_index');
    }

    /**
     * Retourne une liste de tags en fonction d'un critère de recherche
     * @param string $search
     * @return JsonResponse
     */
    #[Route('/ajax/search/{search}', name: 'ajax_search')]
    public function searchTag(string $search = ""): JsonResponse
    {
        /** @var TagRepository $tagRepo */
        $tagRepo = $this->doctrine->getRepository(Tag::class);
        $result = $tagRepo->getByName($search);

        // Si le tag à déjà été saisi pas la peine de le proposer
        $tabTmp = $this->session->get(self::SESSION_KEY_TMP_TAG, []);
        foreach($result as $key => $item)
        {
            if(isset($tabTmp[$item->getId()]))
            {
                unset($result[$key]);
            }
        }

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);
        $result = $serializer->serialize($result, 'json', [AbstractNormalizer::ATTRIBUTES => ['id', 'name', 'color']]);

        return $this->json($result);
    }


    /**
     * Permet de sauvegarder en sessions les tags qui doivent être associés à une autre entité
     * @param Tag $tag
     * @return JsonResponse
     */
    #[Route('/ajax/save-tmp-tags/{id}', name: 'ajax_tmp_save')]
    public function saveTmpTags(Tag $tag): JsonResponse
    {
        $tabTmp = $this->session->get(self::SESSION_KEY_TMP_TAG, []);

        $tabTmp[$tag->getId()] = $tag;
        $this->session->set(self::SESSION_KEY_TMP_TAG, $tabTmp);

        return $this->json(['success' => true]);
    }

    /**
     * Permet d'afficher au format HTML tags stocké en TMP
     * @return Response
     */
    #[Route('/ajax/read-tmp-tags', name: 'ajax_tmp_read')]
    public function readTmpTags(): Response
    {
        $tags = $this->session->get(self::SESSION_KEY_TMP_TAG, []);

        return $this->render('admin/modules/tag/ajax/ajax-tmp-tags.html.twig', [
            'tags' => $tags,
        ]);
    }

    /**
     * Permet de supprimer de la session le tag qui est envoyé en paramètre
     * @param Tag $tag
     * @return JsonResponse
     */
    #[Route('/ajax/delete-tmp-tags/{id}', name: 'ajax_tmp_delete')]
    public function deleteTmpTags(Tag $tag): JsonResponse
    {
        $tabTmp = $this->session->get(self::SESSION_KEY_TMP_TAG, []);

        unset($tabTmp[$tag->getId()]);
        $this->session->set(self::SESSION_KEY_TMP_TAG, $tabTmp);

        return $this->json(['success' => true]);
    }
}
