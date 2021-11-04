<?php
/**
 * Génération du contenu d'un dossier en fontion du mode d'affichage
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Twig\Admin\Media
 */

namespace App\Twig\Admin\Media;

use App\Entity\Media\Folder;
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
                        <img class="img-fluid div-media" src="' . $src . '">
                    <div class="media-name">
                        <span class="badge bg-primary"> ' . $media->getName() . '
                        
                        <div class="dropdown float-end">
                                <div class="btn btn-sm dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                </div>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                    <li><a class="dropdown-item active" href="#">Action</a></li>
                                    <li><a class="dropdown-item" href="#">Another action</a></li>
                                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#">Separated link</a></li>
                                </ul>
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
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                    <li><a class="dropdown-item active" href="#">Action</a></li>
                                    <li><a class="dropdown-item" href="#">Another action</a></li>
                                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#">Separated link</a></li>
                                </ul>
                            </div>
                        </span>
                    </div>
                </div>';
    }

    /**
     * Génère un message quand un dossier est vide
     */
    private function msgFolderEmpty(): string
    {
        return '<div class="text-primary text-center"><i class="fa fa-info"></i> <i>' . $this->translator->trans('admin_media#Le dossier est actuellement vide pour le moment') . '</i></div>';
    }
}