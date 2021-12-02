<?php
/**
 * Controller qui va gérer les questions/réponses pour la GAQ
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Controller\Admin\Module\Faq;
 */

namespace App\Controller\Admin\Modules\FAQ;

use App\Controller\Admin\AppAdminController;
use App\Entity\Modules\FAQ\FaqCategory;
use App\Entity\Modules\FAQ\FaqCategoryTranslation;
use App\Entity\Modules\FAQ\FaqQuestionAnswer;
use App\Entity\Modules\FAQ\FaqQuestionAnswerTranslation;
use App\Form\Modules\FAQ\FaqQuestionAnswerType;
use App\Repository\Modules\FAQ\FaqQuestionAnswerRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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

    /**
     * Permet de créer / éditer une question/réponse de FAQ
     * @param FaqQuestionAnswer|null $faqQuestionAnswer
     * @return RedirectResponse|Response
     */
    #[Route('/add/', name: 'add')]
    #[Route('/edit/{id}', name: 'edit')]
    public function createUpdate(FaqQuestionAnswer $faqQuestionAnswer = null): RedirectResponse|Response
    {
        $frontUrl = $this->generateUrl('front_faq_question_answer', ['slug' => 'slug'], UrlGeneratorInterface::ABSOLUTE_URL);
        $currentLocal = $this->request->getCurrentRequest()->getLocale();

        $breadcrumb = [
            $this->translator->trans('admin_dashboard#Dashboard') => 'admin_dashboard_index',
            $this->translator->trans('admin_system#Modules') => '',
            $this->translator->trans('admin_faq#FAQ') => '',
            $this->translator->trans('admin_faq#Gestion des questions / réponses') => 'admin_faq_question_answer_index',
        ];

        $locales = $this->translationService->getLocales();

        if ($faqQuestionAnswer == null) {

            $oldPosition = 0;

            $action = 'add';
            $faqQuestionAnswer = new FaqQuestionAnswer();

            $locales = $this->translationService->getLocales();
            foreach ($locales as $locale) {
                $faqQuestionAnswerTranslation = new FaqQuestionAnswerTranslation();
                $faqQuestionAnswerTranslation->setLanguage($locale);
                $faqQuestionAnswer->addFaqQuestionAnswerTranslation($faqQuestionAnswerTranslation);
            }

            $title = $this->translator->trans('admin_faq#Ajouter un question / réponse');
            $breadcrumb[$title] = '';
            $flashMsg = $this->translator->trans('admin_faq#Question ajoutée avec succès');
        } else {


            $action = 'edit';
            $title = $this->translator->trans('admin_faq#Edition de la question / réponse ') . '#' . $faqQuestionAnswer->getId();
            $breadcrumb[$title] = '';
            $flashMsg = $this->translator->trans('admin_faq#Question éditée avec succès');
            $oldPosition = $faqQuestionAnswer->getPosition();

            foreach ($locales as $locale) {
                $is_local = false;
                /** @var FaqQuestionAnswerTranslation $faqQuestionAnswerTranslation */
                foreach ($faqQuestionAnswer->getFaqQuestionAnswerTranslations() as $faqQuestionAnswerTranslation) {
                    if ($faqQuestionAnswerTranslation->getLanguage() == $locale) {
                        $is_local = true;
                    }
                }

                if (!$is_local) {
                    $faqQuestionAnswerTranslation = new FaqQuestionAnswerTranslation();
                    $faqQuestionAnswerTranslation->setLanguage($locale);
                    $faqQuestionAnswer->addFaqQuestionAnswerTranslation($faqQuestionAnswerTranslation);
                }
            }
        }

        $form = $this->createForm(FaqQuestionAnswerType::class, $faqQuestionAnswer, ['current_action' => $action, 'current_local' => $currentLocal]);

        $form->handleRequest($this->request->getCurrentRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var FaqQuestionAnswer $faqQuestionAnswer */
            $faqQuestionAnswer = $form->getData();
            $faqQuestionAnswer->setCreateOn(new \DateTime());

            /** @var FaqQuestionAnswerTranslation $faqQuestionAnswerTranslation */
            foreach ($faqQuestionAnswer->getFaqQuestionAnswerTranslations() as $faqQuestionAnswerTranslation) {
                if ($faqQuestionAnswerTranslation->getQuestion() == "" && $faqQuestionAnswerTranslation->getAnswer() == "") {
                    $faqQuestionAnswer->removeFaqQuestionAnswerTranslation($faqQuestionAnswerTranslation);
                }
            }

            $this->faqService->updatePositionFaqQuestionAnswer($faqQuestionAnswer, $oldPosition);
            $this->doctrine->getManager()->persist($faqQuestionAnswer);
            $this->doctrine->getManager()->flush();

            $param = [];
            $this->addFlash('success', $flashMsg);
            if ($action == 'edit') {
                $param = ['page' => $this->getPageInSession(self::SESSION_KEY_PAGE)];
            }

            return $this->redirectToRoute('admin_faq_question_answer_index', $param);
        }

        return $this->render('admin/modules/faq/faq_question_answer/create-update.html.twig', [
            'breadcrumb' => $breadcrumb,
            'form' => $form->createView(),
            'title' => $title,
            'faqQuestionAnswer' => $faqQuestionAnswer,
            'frontUrl' => $frontUrl,
            'currentLocal' => $currentLocal,
            'action' => $action,
        ]);
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
        $returnKo = ['unique' => false, 'msg' => $this->translator->trans('admin_faq#Une question existe déjà avec ce slug')];
        if ($slug == null) {
            return $this->json($returnOk);
        }

        $id = $this->request->getCurrentRequest()->get('id');

        if ($id != null) {
            $params = ['slug' => $slug, 'FaqQuestionAnswer' => $id];
            $result = $this->doctrine->getRepository(FaqQuestionAnswerTranslation::class)->findOneBy($params);

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
        $result = $this->doctrine->getRepository(FaqQuestionAnswerTranslation::class)->findOneBy($params);

        if ($result != null) {
            return $this->json($returnKo);
        }

        return $this->json($returnOk);
    }

    /**
     * Génère une liste d'ordre en fonction d'une catégorie
     * @param FaqCategory $faqCategory
     * @return Response
     */
    #[Route('/ajax/liste-position-category/{id}/', name: 'ajax_liste_position')]
    public function listePosition(FaqCategory $faqCategory): Response
    {
        $currentLocal = $this->request->getCurrentRequest()->getLocale();
        $question_id = $this->request->getCurrentRequest()->get('question_id', 0);

        return $this->render('admin/modules/faq/faq_question_answer/ajax/ajax-liste-position.html.twig',
            [
                'faqCategory' => $faqCategory,
                'currentLocal' => $currentLocal,
                'question_id' => $question_id,
            ]);
    }
}