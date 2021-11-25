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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
}
