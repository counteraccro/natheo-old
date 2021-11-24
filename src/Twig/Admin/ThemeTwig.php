<?php
/**
 * Génération du code HTML pour l'administration des thèmes
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Twig\Admin
 */

namespace App\Twig\Admin;

use Twig\Extension\RuntimeExtensionInterface;

class ThemeTwig extends AppExtension implements RuntimeExtensionInterface
{
    /**
     * Génère un arbre en fonction du contenu du dossier envoyé en paramètre
     * @param string $dir
     * @param int $depth
     * @return string
     */
    public function getTreeByThemeFolder(string $dir, int $depth = 100): string
    {
        $response = '';
        $folder = opendir($dir);

        while ($file = readdir($folder)) {
            if ($file != '.' && $file != '..') {
                $pathFile = $dir . '/' . $file;

                if (is_dir($pathFile)) {
                    $response .= '<li><i class="fa fa-folder"></i> <span>' . $file . '</span>';

                    if (($depth !== 0)) {
                        $response .= '<ul>';
                        $response .= $this->getTreeByThemeFolder($pathFile, $depth - 1);
                        $response .= '</ul>';
                    }
                    $response .= '</li>';
                } else {
                    $response .= '<li><i class="fa fa-file"></i> <a href="' . $pathFile . '">' . $file . '</a></li>';
                }
            }
        }
        closedir($folder);
        return $response;
    }
}