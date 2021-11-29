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
use App\Repository\Modules\FAQ\FaqCategoryRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
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

        /** @var FaqCategoryRepository $faqRepo */
        $faqRepo = $this->doctrine->getRepository(FaqCategory::class);
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

        $tabPositions = $this->faqService->getListeOrderFaqCategory();

        if ($faqCategory == null) {

            $nb = count($tabPositions);
            $tabPositions[$nb+1 . ' -> ' . $this->translator->trans('admin_faq#Nouvelle catégorie')] = $nb+1;
            $oldPosition = $nb+1;

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

            // Affiche la position actuelle de l'élément courant
            $arrayKeys = array_keys($tabPositions);
            $oldKey = '';
            $positionTmp = '';
            foreach($tabPositions as $key => $position)
            {
                if($position === $faqCategory->getPosition())
                {
                    $oldKey = $key;
                    $positionTmp = $position;
                }
            }
            $oldKeyIndex = array_search($oldKey, $arrayKeys);
            $arrayKeys[$oldKeyIndex] = $faqCategory->getPosition() . ' -> ' . $this->translator->trans('admin_faq#Votre position actuel') ;
            $tabPositions =  array_combine($arrayKeys, $tabPositions);

            $action = 'edit';
            $title = $this->translator->trans('admin_faq#Edition de la catégorie ') . '#' . $faqCategory->getId();
            $breadcrumb[$title] = '';
            $flashMsg = $this->translator->trans('admin_faq#Catégorie éditée avec succès');
            $oldPosition = $faqCategory->getPosition();
        }

        $form = $this->createForm(FaqCategoryType::class, $faqCategory, ['positions' => $tabPositions, 'current_action' => $action]);

        $form->handleRequest($this->request->getCurrentRequest());
        if ($form->isSubmitted() && $form->isValid())
        {
            $faqCategory = $form->getData();
            $faqCategory->setCreateOn(new \DateTime());

            $this->faqService->updatePositionFaqCategory($faqCategory, $oldPosition);

            $this->doctrine->getManager()->persist($faqCategory);
            $this->doctrine->getManager()->flush();

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
            'faqCategory' => $faqCategory,
            'frontUrl' => $frontUrl,
            'currentLocal' => $currentLocal,
            'action' => $action,
        ]);
    }

    /**
     * Permet de supprimer une catégorie FAQ
     * @param FaqCategory $faqCategory
     * @param int $confirm
     * @return Response
     */
    #[Route('/delete/{id}/{confirm}', name: 'ajax_delete')]
    public function delete(FaqCategory $faqCategory, int $confirm = 0): Response
    {
        if($confirm == 1)
        {
            $this->doctrine->getManager()->remove($faqCategory);
            $this->doctrine->getManager()->flush();

            return $this->json([
                'msg' => $this->translator->trans('admin_faq#Catégorie supprimé avec succès'),
            ]);
        }

        return $this->render('admin/modules/faq/faq_category/ajax/ajax-modal-delete-category.html.twig', [
            'faqCategory' => $faqCategory,
        ]);
    }

    /**
     * Permet de changer la position une catégorie de FAQ
     * @param FaqCategory $faqCategory
     * @param int $position
     * @return JsonResponse
     */
    #[Route('/change-position/{id}/{position}', name: 'ajax_position')]
    public function changePosition(FaqCategory $faqCategory, int $position): JsonResponse
    {

        $positionTmp = $faqCategory->getPosition();
        $tabPositions = $this->faqService->getListeOrderFaqCategory();
        if($position > 0 && $position < count($tabPositions) && $position != $faqCategory->getPosition())
        {

            $faqCategoryMove = $this->doctrine->getRepository(FaqCategory::class)->getByPosition($position);
            $faqCategory->setPosition($faqCategoryMove->getPosition());
            $faqCategoryMove->setPosition($positionTmp);


            $this->doctrine->getManager()->persist($faqCategory);
            $this->doctrine->getManager()->persist($faqCategoryMove);
            $this->doctrine->getManager()->flush();
        }
        return $this->json(['success' => true]);
    }

    /**
     * Vérifie que le slug est bien unique
     * @param string|null $slug
     * @return JsonResponse
     */
    #[Route('/check-unique-slug/{slug}/', name: 'check_slug')]
    public function checkUniqueSlug(string $slug = null): JsonResponse
    {
        $returnOk = ['unique' => true, 'msg' => ""];
        $returnKo = ['unique' => false, 'msg' => $this->translator->trans('admin_faq#Une catégorie existe déjà avec ce slug')];
        if($slug == null)
        {
            return $this->json($returnOk);
        }

        $id = $this->request->getCurrentRequest()->get('id');

        if($id != null)
        {
            $params = ['slug' => $slug, 'FaqCategory' => $id];
            $result = $this->doctrine->getRepository(FaqCategoryTranslation::class)->findOneBy($params);

            if($result != null)
            {
                // Cas edition et meme slug
                if($result->getSlug() == $slug)
                {
                    return $this->json($returnOk);
                }
                else {
                    return $this->json($returnKo);
                }
            }
        }


        $params = ['slug' => $slug];
        $result = $this->doctrine->getRepository(FaqCategoryTranslation::class)->findOneBy($params);

        if($result != null)
        {
            return $this->json($returnKo);
        }

        return $this->json($returnOk);
    }


}
