<?php
/**
 * Ajout des assets de l'application de façon automatique
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Twig\Admin\System
 */

namespace App\Twig\Admin\System;

use App\Twig\AppExtension;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\StaticVersionStrategy;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Extension\RuntimeExtensionInterface;

class Asset extends AppExtension implements RuntimeExtensionInterface
{

    /**
     * Fichier à charger en premier
     * @var array|string[]
     */
    private array $vipAdminAsset = ['system\System.js'];

    /**
     * Permet de générer les Assets en fonction de $asset
     * @param String $asset
     * @return string
     */
    public function assetAdmin(string $asset): string
    {
        switch ($asset) {
            case "js" :
                return $this->assetAdminJs();
                break;
            default :
                return "";
        }
    }

    /**
     * Permet de créer les URL pour les assets de type JS
     */
    private function assetAdminJs(): string
    {
        $asset = new Package(new StaticVersionStrategy('v1', '%s?version=%s'));
        $finder = new Finder();

        $path_folder = $this->parameterBag->get('app_path_js_admin');
        $path_asset = $this->parameterBag->get('app_path_js_admin_asset');
        $finder->files()->in($path_folder);

        $html = '';
        // Premier passage, ajouter uniquement les fichiers VIP
        foreach ($finder as $file) {

            if(in_array($file->getRelativePathname(), $this->vipAdminAsset))
            {
                $html .= '<script type="text/javascript" src="' . $asset->getUrl($path_asset . $file->getRelativePathname()) . '"></script>';
            }
        }

        // Ajout des autres fichiers
        foreach ($finder as $file) {

            if(in_array($file->getRelativePathname(), $this->vipAdminAsset))
            {
                continue;
            }
            $html .= '<script type="text/javascript" src="' . $asset->getUrl($path_asset . $file->getRelativePathname()) . '"></script>';
        }
        return $html;
    }
}