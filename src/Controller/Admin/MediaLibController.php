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
use App\Entity\Media\Media;
use App\Form\Media\FolderType;
use App\Service\Admin\MediaService;
use App\Service\Admin\System\FileUploaderService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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

        return $this->render('admin/media_lib/ajax-tree-folder.html.twig', [
            'folders' => $folders
        ]);
    }

    /**
     * Affiche le bloc de droite avec les informations sur le dossier courant
     * @param Folder|null $folder
     * @return Response
     */
    #[Route('/folder/{id}', name: 'ajax_see_folder')]
    public function rightBlockFolder(Folder $folder = null): Response
    {
        $folders = null;
        if ($folder == null) {
            $folders = $this->getDoctrine()->getRepository(Folder::class)->findBy(['parent' => null]);
        }

        return $this->render('admin/media_lib/ajax-see-block-folder.html.twig', [
            'folders' => $folders,
            'folder' => $folder
        ]);
    }

    /**
     * Affiche les medias / dossier contenu dans le dossier courant
     * @param Folder|null $folder
     * @return Response
     */
    #[Route('/content-folder/{id}', name: 'ajax_see_content_folder')]
    public function contentFolder(Folder $folder = null): Response
    {

        $data = $this->request->getCurrentRequest()->get('media-filter');

        $folders = null;
        if ($folder == null) {
            $folders = $this->getDoctrine()->getRepository(Folder::class)->findBy(['parent' => null]);
        }

        return $this->render('admin/media_lib/ajax-see-content-folder.html.twig', [
            'folders' => $folders,
            'folder' => $folder
        ]);
    }

    /**
     * Permet de créer un nouveau dossier
     * @param Folder|null $folder
     * @param Folder|null $parent
     * @return Response
     */
    #[Route('/add-folder/{id}/{parent}', name: 'ajax_add_folder')]
    #[Route('/edit-folder/{id}/{parent}', name: 'ajax_edit_folder')]
    #[Entity('parent', options: ['id' => 'parent'])]
    public function createUpdateFolder(Folder $folder = null, Folder $parent = null): Response
    {
        if ($parent == null) {
            $id_parent = -1;
        } else {
            $id_parent = $parent->getId();
        }

        if ($folder == null) {
            $folder = new Folder();
            $title = $this->translator->trans("admin_media#Nouveau dossier");
            $url = $this->generateUrl('admin_media_ajax_add_folder', ['id' => -1, 'parent' => $id_parent]);
            $msg_loading = $this->translator->trans("admin_media#Création du dossier en cours....");
            $action = 'add';
        } else {
            $title = $this->translator->trans("admin_media#Edition du dossier") . " #" . $folder->getId();
            $url = $this->generateUrl('admin_media_ajax_edit_folder', ['id' => $folder->getId(), 'parent' => $id_parent]);
            $msg_loading = $this->translator->trans("admin_media#Mise à jour du dossier en cours....");
            $action = 'edit';
        }

        $form = $this->createForm(FolderType::class, $folder, ['attr' => array(
            'id' => 'form-folder',
            'data-loading' => $msg_loading
        )]);

        $save_ok = false;
        $form->handleRequest($this->request->getCurrentRequest());
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Folder $folder */
            $id = $form->get('refId')->getData();
            if($id != -1)
            {
                $folder = $this->getDoctrine()->getRepository(Folder::class)->findOneBy(['id' => $id]);
                $folder->setName($form->get('name')->getData());
            }
            else {
                $folder = $form->getData();
            }

            $parent = $form->get('parent')->getData();
            // Cas éviter qu'il soit parent de lui-même
            if($parent == null || $parent->getId() != $folder->getId())
            {
                $folder->setParent($parent);
            }

            $this->getDoctrine()->getManager()->persist($folder);
            $this->getDoctrine()->getManager()->flush();
            $save_ok = true;
        }

        return $this->render('admin/media_lib/ajax-modal-create-update-folder.html.twig', [
            'folder' => $folder,
            'parent' => $parent,
            'title' => $title,
            'form' => $form->createView(),
            'url' => $url,
            'save_ok' => $save_ok,
            'action' => $action
        ]);
    }

    /**
     * Modale pour l'ajout de média dans un dossier
     * @param Folder $folder
     * @return Response
     */
    #[Route('/modal-media/{id}', name: 'ajax_modal_media')]
    public function modalAddMedia(Folder $folder): Response
    {
        return $this->render('admin/media_lib/ajax-modal-add-media.html.twig', [
            'folder' => $folder,
        ]);
    }

    /**
     * Permet d'ajouter un ou plusieurs médias dans un dossier
     * @param Folder $folder
     * @param FileUploaderService $fileUploaderService
     * @param MediaService $mediaService
     * @return Response
     */
    #[Route('/upload-media/{id}', name: 'ajax_add_media')]
    public function uploadMedia(Folder $folder, FileUploaderService $fileUploaderService, MediaService $mediaService): Response
    {

        /** @var UploadedFile $file */
        $file = $this->request->getCurrentRequest()->files->get('file');
        $name = $fileUploaderService->upload($file, $fileUploaderService->getMediathequeDirectory());

        $extension = $file->getClientOriginalExtension();

        $nameNoExtension = str_replace('.' . $extension, '', $file->getClientOriginalName());

        $media = new Media();
        $media->setName($nameNoExtension);
        $media->setPath($name);
        $media->setCreateOn(new \DateTime());
        $media->setExtension($extension);
        $media->setFolder($folder);
        $media->setDisabled(false);
        $media->setType($mediaService->getTypeMediaByExtension($extension));

        $this->getDoctrine()->getManager()->persist($media);
        $this->getDoctrine()->getManager()->flush();

        return $this->json(['name' => $name, 'src' => $fileUploaderService->getMediathequePath()]);
    }
}
