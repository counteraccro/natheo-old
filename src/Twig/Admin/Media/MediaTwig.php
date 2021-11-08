<?php
/**
 * Génération du contenu d'un dossier en fontion du mode d'affichage
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Twig\Admin\Media
 */

namespace App\Twig\Admin\Media;

use App\Entity\Media\Folder;
use App\Entity\Media\Media;
use App\Service\Admin\MediaService;
use App\Twig\AppExtension;
use Twig\Extension\RuntimeExtensionInterface;

class MediaTwig extends AppExtension implements RuntimeExtensionInterface
{

    /**
     * Permet d'afficher le contenu d'un dossier en fonction d'un mode d'affichage
     * @param mixed $data
     * @param string $render
     * @return string
     */
    public function renderModeMedia(mixed $data, string $render): string
    {

        $html = match ($render) {
            "grid" => $this->gridMode($data),
            "table" => $this->tableMode($data),
            default => '',
        };
        return $html;
    }

    /**
     * Mode d'affichage en grille
     * @param mixed $data
     * @return string
     */
    private function gridMode(mixed $data): string
    {
        $html = '';

        if ($data instanceof Folder) {

            foreach ($data->getChildren() as $child) {
                $html .= $this->renderFolderGrid($child);
            }

            foreach ($data->getMedia() as $media) {

                switch ($media->getType()) {
                    case MediaService::TYPE_IMAGE :
                        $src = $this->fileUploaderService->getMediathequePath() . '/' . $media->getPath();
                        break;
                    case MediaService::TYPE_FILE :
                        $src = $this->fileUploaderService->getMediathequeDefaultPath() . '/file-default.png';
                        break;
                    case MediaService::TYPE_VIDEO :
                        $src = $this->fileUploaderService->getMediathequeDefaultPath() . '/video-default.png';
                        break;
                    case MediaService::TYPE_AUDIO :
                        $src = $this->fileUploaderService->getMediathequeDefaultPath() . '/audio-default.png';
                        break;
                    default :
                        $src = '';
                        break;
                }

                $html .= '<div class="float-start text-center m-3">
                        <img class="img-fluid img-thumbnail div-media" src="' . $src . '">
                    <div class="media-name">
                        <span class="badge bg-primary"> ' . $media->getName() . '
                        
                        <div class="dropdown float-end">
                                <div class="btn btn-sm dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                </div>
                                ' . $this->getActionMedia($media) . '
                            </div>
                        
                        </span>
                    </div>
                </div>';
            }

            if($html === "")
            {
                $html = $this->msgFolderEmpty();
            }

        } else {

            if(empty($data))
            {
                $html .= $this->msgFolderEmpty();
            }
            else {
                /** @var Folder $folder */
                foreach ($data as $folder) {
                    $html .= $this->renderFolderGrid($folder);
                }
            }
        }

        return $html;
    }

    /**
     * Mode d'affichage sous la forme d'une table
     * @param mixed $data
     */
    private function tableMode(mixed $data): string
    {
        $html = '';

        return $html;
    }

    /**
     * Génère l'affichage des dossiers en mode grid
     * @param Folder $folder
     * @return string
     */
    private function renderFolderGrid(Folder $folder): string
    {
        return '<div class="float-start text-center m-3">
                    <div class="div-folder" 
                    data-url="' . $this->urlGenerator->generate('admin_media_ajax_see_folder', ['id' => $folder->getId()]) . '"
                    data-id="' . $folder->getId() . '"
                    data-loading="' . $this->translator->trans('admin_media#Chargement du dossier') . ' ' . $folder->getName() . '">
                    </div>
                    <div class="folder-name">
                        <span class="badge bg-primary">' . $folder->getName() . '
                            <div class="dropdown float-end">
                                <div class="btn btn-sm dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                </div>
                                ' . $this->getActionFolder($folder) . '
                            </div>
                        </span>
                    </div>
                </div>';
    }

    /**
     * Génère un message quand un dossier est vide
     * @return string
     */
    private function msgFolderEmpty(): string
    {
        return '<div class="text-primary text-center"><i class="fa fa-info"></i> <i>' . $this->translator->trans('admin_media#Le dossier est actuellement vide pour le moment') . '</i></div>';
    }

    /**
     * Permet de générer les actions sur un dossier
     * @param Folder $folder
     * @return string
     */
    private function getActionFolder(Folder $folder): string
    {

        $parent = -1;
        if($folder->getParent() != null)
        {
            $parent = $folder->getParent()->getId();
        }

        $html = '<ul class="dropdown-menu">';

        if($this->accessService->isGranted('admin_media_ajax_edit_folder'))
        {
            $html .= '<li>
                        <a class="dropdown-item btn-edit-folder" 
                            data-loading="' . $this->translator->trans('admin_media#Chargement de la modale pour éditer le dossier') . ' ' . $folder->getName() . '"
                            data-url="' . $this->urlGenerator->generate('admin_media_ajax_edit_folder', ['parent' => $parent, 'id' => $folder->getId() ]) . '">
                            <i class="fa fa-folder"></i> ' . $this->translator->trans('admin_media#Editer') . '
                        </a>
                    </li>';

            $html .= '<li>
                        <a class="dropdown-item btn-edit-folder" 
                            data-loading="' . $this->translator->trans('admin_media#Chargement de la modale pour déplacer le dossier') . ' ' . $folder->getName() . '"
                            data-url="' . $this->urlGenerator->generate('admin_media_ajax_edit_folder', ['parent' => $parent, 'id' => $folder->getId() ]) . '">
                            <i class="fa fa-sync-alt"></i> ' . $this->translator->trans('admin_media#Déplacer') . '
                        </a>
                    </li>';
        }

        if($this->accessService->isGranted('admin_media_ajax_delete_folder')) {
            $html .= '<li>
                        <a class="dropdown-item btn-delete-folder" 
                            data-loading="' . $this->translator->trans('admin_media#Chargement de la modale pour supprimer le dossier') . ' ' . $folder->getName() . '"
                            data-url="' . $this->urlGenerator->generate('admin_media_ajax_delete_folder', ['id' => $folder->getId()]) . '">
                            <i class="fa fa-folder-minus text-danger"></i> ' . $this->translator->trans('admin_media#Supprimer') . '
                        </a>
                    </li>';
        }


        $html .= '</ul>';
        return $html;
    }

    /**
     * Permet de générer les actions sur un media
     * @param Media $media
     * @return string
     */
    private function getActionMedia(Media $media): string
    {

        switch ($media->getType()) {
            case MediaService::TYPE_IMAGE :
                $see = $this->translator->trans('admin_media#Voir l\'image');
                break;
            case MediaService::TYPE_FILE :
                $see = $this->translator->trans('admin_media#Télécharger le fichier');
                break;
            case MediaService::TYPE_VIDEO :
                $see = $this->translator->trans('admin_media#Lire la vidéo');
                break;
            case MediaService::TYPE_AUDIO :
                $see = $this->translator->trans('admin_media#Ecouter le son');
                break;
            default :
                $see = '';
                break;
        }

        $html = '<ul class="dropdown-menu">';
        $html .= '<li>
                        <a class="dropdown-item btn-see-media"
                            target="_blank"
                            href="' . $this->fileUploaderService->getMediathequePath() . '/' . $media->getPath()  . '">
                            <i class="fa fa-eye"></i> ' . $see . '
                        </a>
                    </li>';

        if($this->accessService->isGranted('admin_media_ajax_edit_media')) {
            $html .= '<li>
                        <a class="dropdown-item btn-edit-media" 
                            data-loading="' . $this->translator->trans('admin_media#Chargement de la modale pour editer le media') . ' ' . $media->getName() . '"
                            data-url="' . $this->urlGenerator->generate('admin_media_ajax_edit_media', ['id' => $media->getId()]) . '">
                            <i class="fa fa-pen"></i> ' . $this->translator->trans('admin_media#Editer') . '
                        </a>
                    </li>';

            $html .= '<li>
                        <a class="dropdown-item btn-edit-media" 
                            data-loading="' . $this->translator->trans('admin_media#Chargement de la modale pour déplacer le media') . ' ' . $media->getName() . '"
                            data-url="' . $this->urlGenerator->generate('admin_media_ajax_edit_media', ['id' => $media->getId()]) . '">
                            <i class="fa fa-sync-alt"></i> ' . $this->translator->trans('admin_media#Déplacer') . '
                        </a>
                    </li>';
        }

        if($this->accessService->isGranted('admin_media_ajax_delete_media')) {
            $html .= '<li>
                        <a class="dropdown-item btn-delete-media" 
                            data-loading="' . $this->translator->trans('admin_media#Chargement de la modale pour supprimer le media') . ' ' . $media->getName() . '"
                            data-url="' . $this->urlGenerator->generate('admin_media_ajax_delete_media', ['id' => $media->getId()]) . '">
                            <i class="fa fa-folder-minus text-danger"></i> ' . $this->translator->trans('admin_media#Supprimer') . '
                        </a>
                    </li>';
        }

        if($this->accessService->isGranted('admin_media_ajax_info_media')) {
            $html .= '<li>
                        <a class="dropdown-item btn-delete-media" 
                            data-loading="' . $this->translator->trans('admin_media#Chargement de la modale pour afficher les informations du media') . ' ' . $media->getName() . '"
                            data-url="' . $this->urlGenerator->generate('admin_media_ajax_info_media', ['id' => $media->getId()]) . '">
                            <i class="fa fa-info"></i> ' . $this->translator->trans('admin_media#Information') . '
                        </a>
                    </li>';
        }

        /*
         * <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                    <li><a class="dropdown-item active" href="#">Action</a></li>
                                    <li><a class="dropdown-item" href="#">Another action</a></li>
                                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#">Separated link</a></li>
                                </ul>
         */

        $html .= '</ul>';

        return $html;
    }
}