<?php
/**
 * Controller front de l'application
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Controller
 */
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'front_')]
class FrontController extends AbstractController
{
    #[Route('/', name: 'front')]
    public function index(): Response
    {
        return $this->render('themes/horizon/index.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }
}
