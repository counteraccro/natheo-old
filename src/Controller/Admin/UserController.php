<?php
/**
 * Controller qui va gérer les users
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Controller\Admin;
 */
namespace App\Controller\Admin;

use App\Controller\AppController;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/user', name: 'admin_user_')]
class UserController extends AppController
{

    const SESSION_KEY_FILTER = 'session_user_filter';

    /**
     * Point d'entrée de la gestion des users
     * @return Response
     */
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $breadcrumb = [
            $this->translator->trans('admin_dashboard#Dashboard') => 'admin_dashboard_index',
            $this->translator->trans('admin_user#Gestion des utilisateurs') => '',
        ];

        $fieldSearch = [
            'email' => $this->translator->trans("admin_user#Email"),
        ];

        return $this->render('admin/user/index.html.twig', [
            'breadcrumb' => $breadcrumb,
            'fieldSearch' => $fieldSearch
        ]);
    }

    /**
     * Permet de lister les users
     * @param int $page
     */
    #[Route('/ajax/listing/{page}', name: 'ajax_listing')]
    public function listing(int $page = 1): Response
    {

        $limit = $this->getOptionElementParPage();
        $filter = $this->getCriteriaGeneriqueSearch(self::SESSION_KEY_FILTER);

        /** @var UserRepository $routeRepo */
        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $listeUsers = $userRepo->listeUserPaginate($page, $limit, $filter);

        return $this->render('admin/user/ajax_listing.html.twig', [
            'listeUsers' => $listeUsers,
            'page' => $page,
            'limit' => $limit,
            'route' => 'admin_user_ajax_listing',
        ]);
    }
}
