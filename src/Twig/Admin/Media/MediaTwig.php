<?php
/**
 * Génération du contenu d'un dossier en fontion du mode d'affichage
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Twig\Admin\Media
 */
namespace App\Twig\Admin\Media;

use App\Entity\Media\Folder;
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
     */
    private function gridMode(mixed $data): string
    {
        $html = '';

        if($data instanceof Folder)
        {

        }
        else {

            /** @var Folder $folder */
            foreach($data as $folder)
            {
                $html .= '<div class="float-start text-center m-3 div-folder" 
                    data-url="' . $this->urlGenerator->generate('admin_media_ajax_see_folder', ['id' => $folder->getId()]) . '"
                    data-id="' . $folder->getId() . '"
                    data-loading="' . $this->translator->trans('admin_media#Chargement du dossier') . ' ' . $folder->getName() . '">
                    <br />' . $folder->getName() . '
                    </div>';
            }
        }

        $html .= '';

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
}