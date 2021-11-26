<?php
/**
 * Controller qui va gérer les catégories pour la GAQ
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Controller\Admin\Module\Faq;
 */

namespace App\Controller\Admin\Modules\FAQ;

use App\Controller\Admin\AppAdminController;
use App\Entity\Modules\FAQ\FaqCategory;
use App\Entity\Modules\FAQ\FaqCategoryTranslation;
use App\Form\Modules\FAQ\FaqCategoryType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/admin/faq/category', name: 'admin_faq_category_')]
class FaqCategoryController extends AppAdminController
{

    const SESSION_KEY_FILTER = 'session_faq_category_filter';
    const SESSION_KEY_PAGE = 'session_faq_category_page';

    /**
     * Point d'entrée de la gestion des catégories pour la FAQ
     * @param int $page
     * @return Response
     */
    #[Route('/index/{page}', name: 'index')]
    public function index(int $page = 1): Response
    {
        $breadcrumb = [
            $this->translator->trans('admin_dashboard#Dashboard') => 'admin_dashboard_index',
            $this->translator->trans('admin_system#Modules') => '',
            $this->translator->trans('admin_faq#FAQ') => '',
            $this->translator->trans('admin_faq#Gestion des catégories') => '',
        ];

        $fieldSearch = [
            'title' => $this->translator->trans("admin_faq#Titre"),
            'description' => $this->translator->trans("admin_faq#Description"),
        ];

        return $this->render('admin/modules/faq/faq_category/index.html.twig', [
            'breadcrumb' => $breadcrumb,
            'fieldSearch' => $fieldSearch,
            'page' => $page
        ]);
    }

    /**
     * Permet de lister les catégories de FAQ
     * @param int $page
     * @return Response
     */
    #[Route('/ajax/listing/{page}', name: 'ajax_listing')]
    public function listing(int $page = 1): Response
    {

        $this->setPageInSession(self::SESSION_KEY_PAGE, $page);
        $limit = $this->optionService->getOptionElementParPage();

        $filter = $this->getCriteriaGeneriqueSearch(self::SESSION_KEY_FILTER);

        /** @var FaqCategory $faqRepo */
        $faqRepo = $this->getDoctrine()->getRepository(FaqCategory::class);
        $listeCats = $faqRepo->listeFaqCategoryPaginate($page, $limit, $filter);

        return $this->render('admin/modules/faq/faq_category/ajax/ajax-listing.html.twig', [
            'listeCats' => $listeCats,
            'page' => $page,
            'limit' => $limit,
            'route' => 'admin_faq_category_ajax_listing',
        ]);
    }

    /**
     * Permet de créer / éditer un catégorie de FAQ
     * @param FaqCategory|null $faqCategory
     * @return RedirectResponse|Response
     */
    #[Route('/add/', name: 'add')]
    #[Route('/edit/{id}', name: 'edit')]
    public function createUpdate(FaqCategory $faqCategory = null): RedirectResponse|Response
    {
        $frontUrl = $this->generateUrl('front_faq_cat', ['slug' => 'slug'], UrlGeneratorInterface::ABSOLUTE_URL);
        $currentLocal = $this->request->getCurrentRequest()->getLocale();

        $breadcrumb = [
            $this->translator->trans('admin_dashboard#Dashboard') => 'admin_dashboard_index',
            $this->translator->trans('admin_system#Modules') => '',
            $this->translator->trans('admin_faq#FAQ') => '',
            $this->translator->trans('admin_faq#Gestion des catégories') => 'admin_faq_category_index',
        ];

        if ($faqCategory == null) {
            $action = 'add';
            $faqCategory = new FaqCategory();

            $locales = $this->translationService->getLocales();
            foreach($locales as $locale)
            {
                $faqCategoryTranslation = new FaqCategoryTranslation();
                $faqCategoryTranslation->setLanguage($locale);
                $faqCategory->addFaqCategoryTranslation($faqCategoryTranslation);
            }

            $title = $this->translator->trans('admin_faq#Ajouter un catégorie');
            $breadcrumb[$title] = '';
            $flashMsg = $this->translator->trans('admin_faq#Catégorie crée avec succès');
        } else {
            $action = 'edit';
            $title = $this->translator->trans('admin_faq#Edition de la catégorie ') . '#' . $faqCategory->getId();
            $breadcrumb[$title] = '';
            $flashMsg = $this->translator->trans('admin_faq#Catégorie éditée avec succès');
        }

        $form = $this->createForm(FaqCategoryType::class, $faqCategory);

        $form->handleRequest($this->request->getCurrentRequest());
        if ($form->isSubmitted() && $form->isValid())
        {
            $faqCategory->setCreateOn(new \DateTime());

            $this->getDoctrine()->getManager()->persist($faqCategory);
            $this->getDoctrine()->getManager()->flush();

            $param = [];
            $this->addFlash('success', $flashMsg);
            if ($action == 'edit') {
                $param = ['page' => $this->getPageInSession(self::SESSION_KEY_PAGE)];
            }

            return $this->redirectToRoute('admin_faq_category_index', $param);
        }

        return $this->render('admin/modules/faq/faq_category/create-update.html.twig', [
            'breadcrumb' => $breadcrumb,
            'form' => $form->createView(),
            'title' => $title,
            'tag' => $faqCategory,
            'frontUrl' => $frontUrl,
            'currentLocal' => $currentLocal,
            'action' => $action,
        ]);
    }

    /**
     * Permet de supprimer une catégorie de FAQ
     * @param FaqCategory $faqCategory
     * @return RedirectResponse
     */
    #[Route('/delete/{id}', name: 'delete')]
    public function delete(FaqCategory $faqCategory): RedirectResponse
    {
        /*$flashMsg = $this->translator->trans('admin_tag#Tag supprimé avec succès');
        $this->getDoctrine()->getManager()->remove($tag);
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash('success', $flashMsg);*/
        return $this->redirectToRoute('admin_faq_category_index');
    }

    /**
     * Permet de changer la position une catégorie de FAQ
     * @param FaqCategory $faqCategory
     * @return RedirectResponse
     */
    #[Route('/change-position/{id}', name: 'position')]
    public function changePosition(FaqCategory $faqCategory, int $position): RedirectResponse
    {
        /*$flashMsg = $this->translator->trans('admin_tag#Tag supprimé avec succès');
        $this->getDoctrine()->getManager()->remove($tag);
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash('success', $flashMsg);*/
        return $this->redirectToRoute('admin_faq_category_index');
    }


}
