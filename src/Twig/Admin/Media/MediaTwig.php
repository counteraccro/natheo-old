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
use App\Twig\Admin\AppExtension;
use Twig\Extension\RuntimeExtensionInterface;

class MediaTwig extends AppExtension implements RuntimeExtensionInterface
{

    /**
     * Taille maximum du nom de dossier/media
     * @var int
     */
    private int $max_size_name = 11;

    /**
     * Filtre sur le type
     * @var mixed|string
     */
    private mixed $filtreType = '';

    /**
     * Recherche
     * @var string
     */
    private string $search = '';

    /**
     * Format de la date
     * @var string
     */
    private string $dateFormat = '';

    /**
     * Time format
     * @var string
     */
    private string $timeFormat = '';

    /**
     * Permet d'afficher le contenu d'un dossier en fonction d'un mode d'affichage
     * @param mixed $data
     * @param array $dataFilter
     * @return string
     */
    public function renderModeMedia(mixed $data, array $dataFilter): string
    {

        $this->filtreType = $dataFilter['media'];
        $this->search = $dataFilter['search'];
        $this->timeFormat = $dataFilter['timeFormat'];
        $this->dateFormat = $dataFilter['dateFormat'];

        $html = match ($dataFilter['render']) {
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

                if($this->search != '')
                {
                    $regex = '/' . $this->search . '/';
                    if(!preg_match_all($regex, $child->getName(), $matches, PREG_SET_ORDER, 0))
                    {
                        continue;
                    }
                }

                $html .= $this->renderFolderGrid($child);
            }

            foreach ($data->getMedia() as $media) {

                if($this->filtreType != "all" && $this->filtreType != $media->getType())
                {
                    continue;
                }
                if($this->search != '')
                {
                    $regex = '/' . $this->search . '/';
                    if(!preg_match_all($regex, $media->getName(), $matches, PREG_SET_ORDER, 0))
                    {
                        continue;
                    }
                }

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

                $name = $media->getName();
                if(strlen($media->getName()) > $this->max_size_name)
                {
                    $name = substr($media->getName(), 0, $this->max_size_name) . '...';
                }

                $html .= '<div class="float-start text-center m-3 div-media-g">
                        <img class="img-fluid img-thumbnail div-media" src="' . $src . '">
                    <div class="media-name">
                        <span class="badge bg-primary"> ' . $name . '
                        
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

                    $regex = '/' . $this->search . '/';
                    if(!preg_match_all($regex, $folder->getName(), $matches, PREG_SET_ORDER, 0))
                    {
                        continue;
                    }

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
        $tmp = 0;

        if ($data instanceof Folder) {

            $html .= '<table class="table table-striped align-middle">';
            foreach ($data->getChildren() as $child) {

                if($this->search != '')
                {
                    $regex = '/' . $this->search . '/';
                    if(!preg_match_all($regex, $child->getName(), $matches, PREG_SET_ORDER, 0))
                    {
                        continue;
                    }
                }
                $tmp++;

                $html .= $this->renderFolderTable($child);
            }

            foreach ($data->getMedia() as $media) {

                if ($this->filtreType != "all" && $this->filtreType != $media->getType()) {
                    continue;
                }
                if ($this->search != '') {
                    $regex = '/' . $this->search . '/';
                    if (!preg_match_all($regex, $media->getName(), $matches, PREG_SET_ORDER, 0)) {
                        continue;
                    }
                }

                $tmp++;

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

                $name = $media->getName();

                $html .= '<tr>';
                $html .= '<td><img class="img-fluid img-thumbnail div-media" src="' . $src . '"></td>
                    <td>' . $name . '</td>
                    <td>' . $this->dateService->getDateFormatTranslate($media->getCreateOn(), $this->dateFormat, $this->timeFormat) . '</td>
                    <td>Pages : 0 <br /> Articles : 0</td>
                    <td><div class="dropdown float-end">
                                <div class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                ' . $this->translator->trans('admin_media#Action') . '
                                </div>
                                ' . $this->getActionMedia($media) . '
                            </div></td>';
                $html .= '</tr>';
            }

            if($tmp == 0)
            {
                $html .= '<tr>';
                $html .= '<td colspan=5>' . $this->msgFolderEmpty() . '</td>';
                $html .= '</tr>';
            }

            $html .= '</table>';
        }
        else {
            if(empty($data))
            {
                $html .= $this->msgFolderEmpty();
            }

            $html .= '<table class="table table-striped align-middle">';
            /** @var Folder $folder */
            foreach ($data as $folder) {

                $regex = '/' . $this->search . '/';
                if(!preg_match_all($regex, $folder->getName(), $matches, PREG_SET_ORDER, 0))
                {
                    continue;
                }

                $html .= $this->renderFolderTable($folder);
            }
            $html .= '</table>';
        }

        return $html;
    }

    /**
     * Génère l'affichage des dossiers en mode grid
     * @param Folder $folder
     * @return string
     */
    private function renderFolderGrid(Folder $folder): string
    {
        $name = $folder->getName();
        if(strlen($folder->getName()) > $this->max_size_name)
        {
            $name = substr($folder->getName(), 0, $this->max_size_name) . '...';
        }

        return '<div class="float-start text-center m-3">
                    <div class="div-folder" 
                    data-url="' . $this->urlGenerator->generate('admin_media_ajax_see_folder', ['id' => $folder->getId()]) . '"
                    data-id="' . $folder->getId() . '"
                    data-loading="' . $this->translator->trans('admin_media#Chargement du dossier') . ' ' . $folder->getName() . '">
                    
                    </div>
                    <div class="folder-name">
                        <span class="badge bg-primary">
                        ' . $name . '
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
     * Génère l'affichage des dossiers en mode table
     * @param Folder $folder
     * @return string
     */
    private function renderFolderTable(Folder $folder): string
    {
        $html = '<tr>';

        $html .= '<td>
            <div class="div-folder" 
                    data-url="' . $this->urlGenerator->generate('admin_media_ajax_see_folder', ['id' => $folder->getId()]) . '"
                    data-id="' . $folder->getId() . '"
                    data-loading="' . $this->translator->trans('admin_media#Chargement du dossier') . ' ' . $folder->getName() . '">
        </td>
        <td>' . $folder->getName() . '</td>
        <td></td>
        <td></td>
        <td><div class="dropdown float-end">
                                <div class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                    ' . $this->translator->trans('admin_media#Action') . '
                                </div>
                                ' . $this->getActionFolder($folder) . '
                            </div></td>';

        $html .= '</tr>';

        return $html;
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
                $copy = $this->translator->trans('admin_media#Copier l\'url de l\'image');
                break;
            case MediaService::TYPE_FILE :
                $see = $this->translator->trans('admin_media#Télécharger le fichier');
                $copy = $this->translator->trans('admin_media#Copier l\'url du fichier');
                break;
            case MediaService::TYPE_VIDEO :
                $see = $this->translator->trans('admin_media#Lire la vidéo');
                $copy = $this->translator->trans('admin_media#Copier l\'url de la vidéo');
                break;
            case MediaService::TYPE_AUDIO :
                $see = $this->translator->trans('admin_media#Ecouter le son');
                $copy = $this->translator->trans('admin_media#Copier l\'url du son');
                break;
            default :
                $see = '';
                $copy = '';
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

        $btnIdCopy = "btn-id-copy" . mt_rand();
        $btnIdCopyOk = "btn-id-copy-ok" . mt_rand();
        $hiddenCopy = "txt-to-copy" . mt_rand();

        $html .= '<li>
                        <a class="dropdown-item"
                            target="_blank"
                            href="#">
                            <span id="' . $btnIdCopy . '"><i class="fa fa-copy"></i> ' . $copy . '</span>
                            <input type="hidden" id="' . $hiddenCopy . '" value="'. $this->router->getContext()->getHost() . $this->fileUploaderService->getMediathequePath() . '/' . $media->getPath()  . '">
                            <span id="' . $btnIdCopyOk . '"><i class="fa fa-check"></i> ' . $this->translator->trans('admin_media#URL copiée !') . '</span>
                        </a>
                        
                        <script>
                            System.copy("#' . $btnIdCopy . '", "#' . $hiddenCopy . '", "#' . $btnIdCopyOk . '");
                        </script>
                        
                        
                    </li>';

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