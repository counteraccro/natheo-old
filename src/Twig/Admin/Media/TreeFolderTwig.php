<?php
/**
 * Génération de la liste des dossiers des médias
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Twig\Admin\Media
 */

namespace App\Twig\Admin\Media;

use App\Entity\Media\Folder;
use App\Twig\AppExtension;
use Doctrine\Common\Collections\Collection;
use Twig\Extension\RuntimeExtensionInterface;

class TreeFolderTwig extends AppExtension implements RuntimeExtensionInterface
{
    /**
     * Génère le code HTML pour la construction du tree de dossier
     * @param array $folders
     * @return string
     */
    public function treeFolder(array $folders)
    {
        $fragment = '';

        $html = '<ul><li><span class="caret caret-down activeNode" data-id="-1">Root</span>
            <ul class="nested active">';

        foreach ($folders as $folder) {
            $html .= $this->readFolder($folder, $fragment);
        }
        $html .= '</ul></ul>';

        return $html;
    }

    /**
     * Permet de lire le contenu d'un dossier de façon récursive
     * @param Folder $folder
     * @param string $html
     * @return string|void
     */
    private function readFolder(Folder $folder, string $html)
    {
        $html .= '<li><span class="caret" data-id="' . $folder->getId() . '" data-url="' . $this->urlGenerator->generate('admin_media_ajax_see_folder', ['id' => $folder->getId()]) . '">' . $folder->getName() . '</span>';
        $html .= '<ul class="nested">';

        if ($folder->getChildren()->count() != 0) {

            /** @var Folder $child */
            foreach ($folder->getChildren() as $child) {
                $html = $this->readFolder($child, $html);
            }
        }
        $html .= $this->showTypeContent($folder);

        $html .= '</ul></li>';
        return $html;
    }

    /**
     * Permet de voir le contenu d'un dossier (media)
     * @param Folder $folder
     * @return string
     */
    private function showTypeContent(Folder $folder): string
    {
        return '<li><i class="fa fa-file-image"></i> ' . $this->translator->trans('admin_media#Images') . '
                            <span class="badge rounded-pill bg-primary">
                                99+
                             </span>
                        </li>
                        <li><i class="fa fa-file-word"></i> ' . $this->translator->trans('admin_media#Fichiers') . '
                            <span class="badge rounded-pill bg-primary">
                                99+
                             </span>
                        </li>
                        <li><i class="fa fa-file-video"></i> ' . $this->translator->trans('admin_media#Vidéos') . '
                            <span class="badge rounded-pill bg-primary">
                                99+
                             </span>
                        </li>
                        <li><i class="fa fa-file-audio"></i> ' . $this->translator->trans('admin_media#Audio') . '
                            <span class="badge rounded-pill bg-primary">
                                99+
                             </span>
                        </li>';
    }

    /**
     * Défini le path complet du dossier
     * @param Folder $folder
     */
    public function getPathFolder(Folder $folder = null): string
    {
        $tab = [];
        if ($folder != null) {
            $tab = $this->generatePath($folder, $tab);
        }
        $tab = array_reverse($tab);

        $html = '<nav style="--bs-breadcrumb-divider: \'>\';" aria-label="breadcrumb" id="breadcrumb-folder-media">
              <ol class="breadcrumb">';
        if(empty($tab))
        {
            $html .= ' <li class="breadcrumb-item active" aria-current="page">Root</a></li>';
        }
        else {
            $html .=  '<li class="breadcrumb-item"><a data-id="-1" href="' . $this->urlGenerator->generate('admin_media_ajax_see_folder', ['id' => '-1']) . '">Root</a></li>';
        }
        foreach ($tab as $tabFolder) {
            if ($folder->getName() == $tabFolder['name']) {
                $html .= '<li class="breadcrumb-item active" aria-current="page">' . $tabFolder['name'] . '</li>';
            } else {
                $html .= '<li class="breadcrumb-item"><a data-id="' . $tabFolder['id'] . '" href="' . $this->urlGenerator->generate('admin_media_ajax_see_folder', ['id' => $tabFolder['id']]) . '">' . $tabFolder['name'] . '</a></li>';
            }
        }
        $html .= '</ol>
            </nav>';
        return $html;
    }

    /**
     * Génère le path complet du dossier de façon récursive sous la forme d'un tableau
     * @param Folder $folder
     * @param array $tab
     * @return array
     */
    private function generatePath(Folder $folder, array $tab): array
    {
        $tab[] = ['name' => $folder->getName(), 'id' => $folder->getId()];
        if ($folder->getParent() != null) {
            return $this->generatePath($folder->getParent(), $tab);
        } else {
            return $tab;
        }
    }
}