<?php
/**
 * Génération du code HTML pour l'administration des thèmes
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Twig\Admin
 */
namespace App\Twig\Admin;

use App\Twig\AppExtension;
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
        $template = array('<ul>', '<li><i class="fa fa-{icon}"></i> <a href="{path}">{file}</a></li>', '</ul>');

        $response = '';
        $folder = opendir($dir);

        while ($file = readdir($folder)) {
            if ($file != '.' && $file != '..') {
                $pathFile = $dir . '/' . $file;

                if (is_dir($pathFile)) {
                    $response .= str_replace(array('{path}', '{file}', '{icon}'), array($pathFile, $file, 'folder'), $template[1]);
                } else {
                    $response .= str_replace(array('{path}', '{file}', '{icon}'), array($pathFile, $file, 'file'), $template[1]);
                }
                if (is_dir($pathFile) && ($depth !== 0))
                    $response .= $this->getTreeByThemeFolder($pathFile, $depth - 1);
            }
        }
        closedir($folder);
        return $template[0] . $response . $template[2];
    }
}