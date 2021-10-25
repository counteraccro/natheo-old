<?php
/**
 * Controller qui va gérer les médialib
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Controller\Admin;
 */
namespace App\Controller\Admin;

use App\Controller\AppController;
use App\Entity\Media\Folder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/media', name: 'admin_media_')]
class MediaLibController extends AppController
{
    /**
     * @return Response
     */
    #[Route('/index', name: 'index')]
    public function index(): Response
    {

        $breadcrumb = [
            $this->translator->trans('admin_dashboard#Dashboard') => 'admin_dashboard_index',
            $this->translator->trans('admin_media#Médiathèque') => '',
        ];

        return $this->render('admin/media_lib/index.html.twig', [
            'breadcrumb' => $breadcrumb,
        ]);
    }

    /**
     * Affichage des dossiers des médias
     * @return Response
     */
    #[Route('/tree-view-folder', name: 'ajax_tree_folder')]
    public function treeViewFolder(): Response
    {

        $folders = $this->getDoctrine()->getRepository(Folder::class)->findBy(['parent' => null]);

        return $this->render('admin/media_lib/tree-folder.html.twig', [
            'folders' => $folders
        ]);
    }

    /**
     * Permet de voir le contenu d'un dossier
     * @param Folder|null $folder
     * @return Response
     */
    #[Route('/folder/{id}', name: 'ajax_see_folder')]
    public function contentFolder(Folder $folder = null): Response
    {
        $folders = null;
        if($folder == null)
        {
            $folders = $this->getDoctrine()->getRepository(Folder::class)->findBy(['parent' => null]);
        }

        return $this->render('admin/media_lib/see-content-folder.html.twig', [
            'folders' => $folders,
            'folder' => $folder
        ]);
    }
}
