<?php
/**
 * Code pour les médias
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Service\Admin
 */
namespace App\Service\Admin;

use App\Entity\Media\Folder;
use App\Service\AppService;

class MediaService extends AppService
{
    const TYPE_IMAGE = 1;
    const TYPE_IMAGE_STR = "admin_media#Image";
    const TYPE_FILE = 2;
    const TYPE_FILE_STR = "admin_media#Fichier";
    const TYPE_VIDEO = 3;
    const TYPE_VIDEO_STR = "admin_media#Vidéo";
    const TYPE_AUDIO = 4;
    const TYPE_AUDIO_STR = "admin_media#Audio";

    /**
     * Permet d'optenir le type de média en fonction de l'extension
     * @param String $extension
     */
    public function getTypeMediaByExtension(String $extension): int
    {
        return match ($extension) {
            "png", "gif", "tif", "tiff", "jpeg", "jpg", "bmp", "jp2" => self::TYPE_IMAGE,
            "pdf", "doc", "docx", "txt", "ppt", "xls" => self::TYPE_FILE,
            "flv", "avi", "mp3", "mpg", "mpeg", "mp4", "webm" => self::TYPE_VIDEO,
            "mid", "wav", => self::TYPE_AUDIO,
            default => 0,
        };
    }

    /**
     * Permet de supprimer l'ensemble des enfants d'un dossier
     * @param Folder $folder
     */
    public function deleteChildrenFolder(Folder $folder)
    {
        $this->doctrine->getManager();

        /** @var Folder $child */
        foreach ($folder->getChildren() as $child)
        {
            $this->deleteChildrenFolder($child);
        }
        $this->doctrine->getManager()->remove($folder);
        $this->doctrine->getManager()->flush();
    }

    /**
     * Retourne un tableau contenant les statistiques d'un dossier
     * @param Folder|null $folder
     * @return array
     */
    public function getStatByTypeInFolder(Folder $folder = null): array
    {
        $tab = [
            'all' => 0,
            self::TYPE_IMAGE => 0,
            self::TYPE_VIDEO => 0,
            self::TYPE_FILE => 0,
            self::TYPE_AUDIO => 0
        ];

        if($folder == null)
        {
            return $tab;
        }

        foreach($folder->getMedia() as $media)
        {
            $tab['all']++;

            switch ($media->getType()) {
                case self::TYPE_IMAGE:
                    $tab[self::TYPE_IMAGE]++;
                    break;
                case self::TYPE_FILE:
                    $tab[self::TYPE_FILE]++;
                    break;
                case self::TYPE_VIDEO:
                    $tab[self::TYPE_VIDEO]++;
                    break;
                case self::TYPE_AUDIO:
                    $tab[self::TYPE_AUDIO]++;
                    break;
            }
        }

        return $tab;
    }
}