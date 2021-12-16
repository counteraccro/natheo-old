<?php
/**
 * Controller pour la gestion des pages l'application
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Controller\Admin
 */

namespace App\Controller\Admin;

use App\Entity\Admin\Page\Page;
use App\Entity\Admin\Page\PageTag;
use App\Entity\Admin\Page\PageTranslation;
use App\Entity\Modules\Tag;
use App\Form\Admin\Page\PageType;
use App\Repository\Admin\Page\PageRepository;
use App\Service\Admin\PageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\Date;

#[Route('/admin/page', name: 'admin_page_')]
class PageController extends AppAdminController
{
    const SESSION_KEY_FILTER = 'session_page_filter';
    const SESSION_KEY_PAGE = 'session_page_page';

    /**
     * Point d'entrée pour les pages
     * @param int $page
     * @return Response
     */
    #[Route('/index/{page}', name: 'index')]
    public function index(int $page = 1): Response
    {
        $this->tagService->resetTmpTag();

        $breadcrumb = [
            $this->translator->trans('admin_dashboard#Dashboard') => 'admin_dashboard_index',
            $this->translator->trans('admin_page#Gestion des pages') => '',
        ];

        $fieldSearch = [
            'pageTitle' => $this->translator->trans("admin_page#Titre"),
            'navigationTitle' => $this->translator->trans("admin_page#Titre navigation"),
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
        $this->setPageInSession(self::SESSION_KEY_PAGE, $page);
        $limit = $this->optionService->getOptionElementParPage();
        $filter = $this->getCriteriaGeneriqueSearch(self::SESSION_KEY_FILTER);
        $dateFormat = $this->optionService->getOptionShortFormatDate();
        $timeFormat = $this->optionService->getOptionTimeFormat();

        /** @var PageRepository $pageRepo */
        $pageRepo = $this->doctrine->getRepository(Page::class);
        $listePages = $pageRepo->listePaginate($page, $limit, $filter);
        return $this->render('admin/page/ajax/ajax-listing.html.twig', [
            'listePages' => $listePages,
            'page' => $page,
            'limit' => $limit,
            'dateFormat' => $dateFormat,
            'timeFormat' => $timeFormat,
            'route' => 'admin_page_ajax_listing',
        ]);
    }

    /**
     * Permet de créer / éditer une page
     * @param Page|null $page
     * @return Response
     */
    #[Route('/add/', name: 'add')]
    #[Route('/edit/{id}', name: 'edit')]
    public function createUpdate(Page $page = null): Response
    {

        $currentLocale = $this->request->getCurrentRequest()->getLocale();
        $locales = $this->translationService->getLocales();
        $frontUrl = $this->generateUrl('front_front_slug', ['slug' => 'slug'], UrlGeneratorInterface::ABSOLUTE_URL);

        $breadcrumb = [
            $this->translator->trans('admin_dashboard#Dashboard') => 'admin_dashboard_index',
            $this->translator->trans('admin_page#Gestion des pages') => 'admin_page_index',
        ];

        if ($page == null) {

            $action = 'add';
            $currentId = 0;
            $title = $this->translator->trans('admin_page#Créer une page');
            $breadcrumb[$title] = '';

            $page = new Page();

            $locales = $this->translationService->getLocales();
            foreach ($locales as $locale) {
                $pageTranslation = new PageTranslation();
                $pageTranslation->setLanguage($locale);
                if($currentLocale != $locale)
                {
                    $pageTranslation->setPageTitle(PageService::DEFAULT_NAME_PAGE_TITLE);
                    $pageTranslation->setNavigationTitle(PageService::DEFAULT_NAME_NAVIGATION_TITLE);
                }

                $page->addPageTranslation($pageTranslation);
            }

        } else {

            $action = 'edit';
            $title = $this->translator->trans('admin_page#Edition de la page ') . '#' . $page->getId();
            $breadcrumb[$title] = '';
            $currentId = $page->getId();

            foreach ($locales as $locale) {
                $is_local = false;
                /** @var PageTranslation $pageTranslation */
                foreach ($page->getPageTranslations() as $pageTranslation) {
                    if ($pageTranslation->getLanguage() == $locale) {
                        $is_local = true;
                    }
                }

                if (!$is_local) {
                    $pageTranslation = new PageTranslation();
                    $pageTranslation->setLanguage($locale);
                    $pageTranslation->setPageTitle(PageService::DEFAULT_NAME_PAGE_TITLE);
                    $pageTranslation->setNavigationTitle(PageService::DEFAULT_NAME_NAVIGATION_TITLE);
                    $page->addPageTranslation($pageTranslation);
                }
            }
        }

        $form = $this->createForm(PageType::class, $page, ['current_id' => $currentId, 'current_locale' => $currentLocale]);
        $form->handleRequest($this->request->getCurrentRequest());
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Page $page */
            $page = $form->getData();

            if($action == 'add')
            {
                $page->setCreateOn(new \DateTime());
            }

            $page->setEditedOn(new \DateTime());
            $page->setUser($this->getUser());

            foreach($page->getPageTranslations() as $pageTranslation)
            {
                if($pageTranslation->getPageTitle() == PageService::DEFAULT_NAME_PAGE_TITLE && $pageTranslation->getNavigationTitle() == PageService::DEFAULT_NAME_NAVIGATION_TITLE)
                {
                    $page->removePageTranslation($pageTranslation);
                }
            }

            // On remet les tags selectionnés
            $tags = $this->tagService->readTmpTag();
            foreach ($tags as $tag) {
                $pageTag = new PageTag();
                $tag = $this->doctrine->getRepository(Tag::class)->findOneBy(['id' => $tag->getId()]);
                $pageTag->setTag($tag);
                $pageTag->setCreateOn(new \DateTime());
                $pageTag->setPage($page);
                $pageTag->setUser($this->getUser());
                $page->addTag($pageTag);
            }

            $this->doctrine->getManager()->persist($page);
            $this->doctrine->getManager()->flush();

            $page = $this->getPageInSession(self::SESSION_KEY_PAGE);
            return $this->redirectToRoute('admin_page_index', ['page' => $page]);
        }

        foreach ($page->getTags() as $pageTags) {
            $tag = $pageTags->getTag();
            $name = $tag->getName();
            $this->tagService->addTmptag($tag);
        }

        return $this->render('admin/page/create-update.html.twig', [
            'breadcrumb' => $breadcrumb,
            'title' => $title,
            'locales' => $locales,
            'currentLocal' => $currentLocale,
            'page' => $page,
            'form' => $form->createView(),
            'frontUrl' => $frontUrl,
            'action' => $action
        ]);
    }

    /**
     * Permet de selectionner un template
     * @return Response
     */
    #[Route('/ajax/select-template', name: 'ajax_select_template')]
    public function selectTemplate(): Response
    {
        $tabTemplate = $this->pageService->getSelectedTemplate();

        return $this->render('admin/page/ajax/ajax-modal-select-template.html.twig', [
            'id' => $tabTemplate['id']
        ]);
    }

    /**
     * Permet de sauvegarder le template selectionné
     * @param int $id
     * @return Response
     */
    #[Route('/ajax/save-select-template/{id}', name: 'ajax_select_save_template')]
    public function saveSelectTemplate(int $id = 0): Response
    {
        $this->pageService->setSelectedTemplate($id);

        return $this->json(['success' => true]);
    }

    /**
     * Permet de charger le template selectionné pour l'édition / création d'une page
     * @return Response
     */
    #[Route('/ajax/load-template', name: 'ajax_load_template')]
    public function loadTemplate(): Response
    {
        $tabTemplate = $this->pageService->getSelectedTemplate();

        return $this->render('admin/page/ajax/ajax-template.html.twig', [
            'template' => $tabTemplate['base']
        ]);

    }

    /**
     * Vérifie que le slug est bien unique
     * @param string|null $slug
     * @param string $language
     * @return JsonResponse
     */
    #[Route('/ajax/check-unique-slug/{slug}/{language}', name: 'ajax_check_slug')]
    public function checkUniqueSlug(string $slug = null, string $language = 'fr'): JsonResponse
    {
        $returnOk = ['unique' => true, 'msg' => ""];
        $returnKo = ['unique' => false, 'msg' => $this->translator->trans('admin_page#Une page existe déjà avec ce slug')];
        if ($slug == null) {
            return $this->json($returnOk);
        }

        $id = $this->request->getCurrentRequest()->get('id');

        if ($id != null) {
            $params = ['slug' => $slug, 'page' => $id, 'language' => $language];
            $result = $this->doctrine->getRepository(PageTranslation::class)->findOneBy($params);

            if ($result != null) {

                // Cas edition et meme slug
                if ($result->getSlug() == $slug) {
                    return $this->json($returnOk);
                } else {
                    return $this->json($returnKo);
                }
            }
        }

        $params = ['slug' => $slug];
        $result = $this->doctrine->getRepository(PageTranslation::class)->findOneBy($params);

        if ($result != null) {
            return $this->json($returnKo);
        }

        return $this->json($returnOk);
    }

    /**
     * Permet de supprimer une page
     * @param Page $page
     * @param int $confirm
     * @return Response
     */
    #[Route('/delete/{id}/{confirm}', name: 'ajax_delete')]
    public function delete(Page $page, int $confirm = 0): Response
    {
        if($confirm == 1)
        {
            $this->doctrine->getManager()->remove($page);
            $this->doctrine->getManager()->flush();

            return $this->json([
                'msg' => $this->translator->trans('admin_page#Page supprimée avec succès'),
            ]);
        }

        return $this->render('admin/page/ajax/ajax-modal-delete-page.html.twig', [
            'page' => $page,
        ]);
    }
}
