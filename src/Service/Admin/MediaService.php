<?php
/**
 * Code pour les médias
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Service\Admin
 */
namespace App\Service\Admin;

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
            "flv", "avi", "mp3", "mpg", "mpeg" => self::TYPE_VIDEO,
            "mid", "wav", => self::TYPE_AUDIO,
            default => 0,
        };
    }
}