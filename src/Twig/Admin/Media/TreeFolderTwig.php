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

        $html = '<ul><li><span class="caret">Root</span>
            <ul class="nested">';

        foreach($folders as $folder)
        {
            $html .= $this->readFolder($folder, $fragment);
        }
        $html .= '</ul></ul>';

        return $html;
    }

    /**
     * @param Folder $folder
     * @param string $html
     * @return string|void
     */
    private function readFolder(Folder $folder, string $html)
    {
        if($folder->getChildren()->count() == 0)
        {
            $html .= '<li>' . $folder->getName() . '</li>';
            return $html;
        }
        else {
            $html .= '<li><span class="caret">' . $folder->getName() . '</span>';
            $html .= '<ul class="nested">';
            /** @var Folder $child */
            foreach($folder->getChildren() as $child)
            {
                $html = $this->readFolder($child, $html);
            }
            $html .= '</ul>';
            return $html;
        }
    }
}