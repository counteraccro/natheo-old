<?php
/**
 * Controller qui va gérer les questions/réponses pour la GAQ
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Controller\Admin\Module\Faq;
 */
namespace App\Controller\Admin\Modules\FAQ;

use App\Controller\Admin\AppAdminController;
use App\Entity\Modules\FAQ\FaqQuestionAnswer;
use App\Repository\Modules\FAQ\FaqQuestionAnswerRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/faq/question-answer', name: 'admin_faq_question_answer_')]
class FaqQuestionAnswerController extends AppAdminController
{

    const SESSION_KEY_FILTER = 'session_faq_question_answer_filter';
    const SESSION_KEY_PAGE = 'session_faq_question_answer_category_page';

    /**
     * Point d'entrée pour la gestion des questions/réponses
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
            $this->translator->trans('admin_faq#Gestion des questions / réponses') => '',
        ];

        $fieldSearch = [
            'question' => $this->translator->trans("admin_faq#Question"),
            'answer' => $this->translator->trans("admin_faq#Réponse"),
            'title' => $this->translator->trans("admin_faq#Catégorie")
        ];

        return $this->render('admin/modules/faq/faq_question_answer/index.html.twig', [
            'breadcrumb' => $breadcrumb,
            'fieldSearch' => $fieldSearch,
            'page' => $page
        ]);
    }

    /**
     * Permet de lister les questions/réponses de FAQ
     * @param int $page
     * @return Response
     */
    #[Route('/ajax/listing/{page}', name: 'ajax_listing')]
    public function listing(int $page = 1): Response
    {

        $this->setPageInSession(self::SESSION_KEY_PAGE, $page);
        $limit = $this->optionService->getOptionElementParPage();

        $filter = $this->getCriteriaGeneriqueSearch(self::SESSION_KEY_FILTER);

        /** @var FaqQuestionAnswerRepository $faqRepo */
        $faqQuestionAnswerRepo = $this->doctrine->getRepository(FaqQuestionAnswer::class);
        $listeQuestionAnswer = $faqQuestionAnswerRepo->listePaginate($page, $limit, $filter);

        return $this->render('admin/modules/faq/faq_question_answer/ajax/ajax-listing.html.twig', [
            'listeQuestionAnswer' => $listeQuestionAnswer,
            'page' => $page,
            'limit' => $limit,
            'route' => 'admin_faq_question_answer_ajax_listing',
        ]);
    }
}
