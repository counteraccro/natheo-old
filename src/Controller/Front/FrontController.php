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
    public function index(): Response
    {
        $theme = $this->themeService->getThemeSelected();

        return $this->render('themes/' . $theme->getFolderRef() . '/index.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }
}
