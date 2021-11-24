<?php
/**
 * Gestion des path et images pour les vues
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Twig\Admin\System
 */
namespace App\Twig\Admin\System;

use App\Twig\Admin\AppExtension;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\StaticVersionStrategy;
use Twig\Extension\RuntimeExtensionInterface;

class FileTwig extends AppExtension  implements RuntimeExtensionInterface
{
    /**
     * Retourne le path pour les avatars
     * @return string
     */
    public function getPathAvatar(string $img): string
    {
        $asset = new Package(new StaticVersionStrategy('v1', '%s?version=%s'));
        return $asset->getUrl($this->fileUploaderService->getAvatarPath() . DIRECTORY_SEPARATOR . $img);
    }
}