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
use App\Form\Admin\UserType;
use App\Repository\UserRepository;
use App\Service\Admin\System\FileService;
use App\Service\Admin\System\FileUploaderService;
use App\Service\Admin\System\OptionService;
use App\Service\Admin\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/user', name: 'admin_user_')]
class UserController extends AppController
{

    const SESSION_KEY_FILTER = 'session_user_filter';
    const SESSION_KEY_PAGE = 'session_user_page';

    /**
     * Point d'entrée de la gestion des users
     * @return Response
     */
    #[Route('/index/{page}', name: 'index')]
    public function index($page = 1): Response
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
            'fieldSearch' => $fieldSearch,
            'page' => $page
        ]);
    }

    /**
     * Permet de lister les users
     * @param int $page
     */
    #[Route('/ajax/listing/{page}', name: 'ajax_listing')]
    public function listing(int $page = 1): Response
    {

        $this->setPageInSession(self::SESSION_KEY_PAGE, $page);
        $limit = $this->getOptionElementParPage();
        $dateFormat =$this->getOptionShortFormatDate();
        $timeFormat = $this->getOptionTimeFormat();

        $filter = $this->getCriteriaGeneriqueSearch(self::SESSION_KEY_FILTER);

        /** @var UserRepository $routeRepo */
        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $listeUsers = $userRepo->listeUserPaginate($page, $limit, $filter);

        return $this->render('admin/user/ajax-listing.html.twig', [
            'listeUsers' => $listeUsers,
            'page' => $page,
            'limit' => $limit,
            'dateFormat' => $dateFormat,
            'timeFormat' => $timeFormat,
            'route' => 'admin_user_ajax_listing',
        ]);
    }

    /**
     * Permet de créer / éditer un user
     * @param User|null $user
     */
    #[Route('/add/', name: 'add')]
    #[Route('/edit/{id}', name: 'edit')]
    public function createUpdate(FileUploaderService $fileUploader, UserPasswordHasherInterface $passwordHarsher, User $user = null): RedirectResponse|Response
    {

        $breadcrumb = [
            $this->translator->trans('admin_dashboard#Dashboard') => 'admin_dashboard_index',
            $this->translator->trans('admin_user#Gestion des utilisateurs') => ['admin_user_index', ['page' => $this->getPageInSession(self::SESSION_KEY_PAGE)]]
        ];

        $dateFormat =$this->getOptionFormatDate();
        $timeFormat = $this->getOptionTimeFormat();

        if($user == null)
        {
            $action = 'add';
            $user = new User();
            $title = $this->translator->trans('admin_user#Créer un utilisateur');
            $breadcrumb[$title] = '';
            $flashMsg = $this->translator->trans('admin_user#Utilisateur créé avec succès');
        }
        else {

            // Si on tente d'éditer un user spécificque, on rejette la demande
            if($user->getName() == UserService::ROOT_NAME || $user->getName() == UserService::GHOST_NAME)
            {
                return $this->redirectToRoute('admin_user_index');
            }

            $action = 'edit';
            $title = $this->translator->trans('admin_user#Edition de l\'utilisateur ') . '#' . $user->getId();
            $breadcrumb[$title] = '';
            $flashMsg = $this->translator->trans('admin_user#Utilisateur édité avec succès');
        }

        $form = $this->createForm(UserType::class, $user, ['custom_action' => $action]);

        $form->handleRequest($this->request->getCurrentRequest());
        if ($form->isSubmitted() && $form->isValid()) {

            $avatar = $form->get('avatar')->getData();
            if ($avatar) {
                $avatarName = $fileUploader->upload($avatar, $fileUploader->getAvatarDirectory());
                $this->fileService->delete($user->getAvatar(), $fileUploader->getAvatarDirectory());
                $user->setAvatar($avatarName);
            }
            else if($action == 'add')
            {
                $user->setAvatar(UserService::DEFAULT_AVATAR);
            }

            $password = $form->get('password')->getData();
            if($password != "")
            {
                $user->setPassword($passwordHarsher->hashPassword(
                    $user,
                    $password
                ));

                $user->setLastPasswordUpdae(new \DateTime());
            }


            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', $flashMsg);

            $param = [];
            if($action == 'edit')
            {
                $param = ['page' => $this->getPageInSession(self::SESSION_KEY_PAGE)];
            }
            return $this->redirectToRoute('admin_user_index', $param);
        }

        return $this->render('admin/user/create-update.html.twig', [
            'breadcrumb' => $breadcrumb,
            'form' => $form->createView(),
            'title' => $title,
            'user' => $user,
            'dateFormat' => $dateFormat,
            'timeFormat' => $timeFormat,
            'action' => $action
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(User $user, FileUploaderService $fileUploaderService): RedirectResponse
    {
        $this->fileService->delete($user->getAvatar(), $fileUploaderService->getAvatarDirectory());
        $flashMsg = $this->translator->trans('admin_user#Utilisateur supprimé avec succès');

        if($this->getOptionReplaceUser() == OptionService::GO_ADM_REPLACE_USER_YES_VALUE)
        {
            $id_user = $user->getId();
            // TODO Code pour remplacer les données du user par john doe
        }

        $this->getDoctrine()->getManager()->remove($user);
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash('success', $flashMsg);
        return $this->redirectToRoute('admin_user_index');
    }
}
