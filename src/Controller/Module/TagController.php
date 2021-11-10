<?php
/**
 * Controller qui les tags
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Controller\Module
 */
namespace App\Controller\Module;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/module/tag', name: 'front_tag_index')]
class TagController extends AbstractController
{
    #[Route('/module/tag', name: 'index')]
    public function index(): Response
    {
        return $this->render('module/tag/index.html.twig', [
            'controller_name' => 'TagController',
        ]);
    }
}
