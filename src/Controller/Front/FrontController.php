<?php
/**
 * Controller front de l'application
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Controller
 */
namespace App\Controller\Front;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'front_')]
class FrontController extends AppFrontController
{
    #[Route('/', name: 'front')]
    #[Route('/page/{slug}', name: 'front_slug')]
    public function index(string $slug = null): Response
    {
        $theme = $this->themeService->getThemeSelected();

        return $this->render('themes/' . $theme->getFolderRef() . '/views/index.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }

    /**
     * @param string $slug
     * @return Response
     */
    #[Route('/modules/faq/category/{slug}', name: 'faq_cat')]
    public function faqCat(string $slug): Response
    {

        $theme = $this->themeService->getThemeSelected();
        return $this->render('themes/' . $theme->getFolderRef() . '/views/index.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }

    /**
     * @param string $slug
     * @return Response
     */
    #[Route('/modules/faq/question/{slug}', name: 'faq_question_answer')]
    public function faqQuestionAnswer(string $slug): Response
    {
        $theme = $this->themeService->getThemeSelected();
        return $this->render('themes/' . $theme->getFolderRef() . '/views/index.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }
}
