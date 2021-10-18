<?php
/**
 * Gère les upload sur l'applications
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Service\Admin\System
 */
namespace App\Service\Admin\System;

use App\Service\AppService;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class FileUploaderService extends AppService
{

    /**
     * Permet de upload un fichier
     * @param UploadedFile $file
     * @param string $directory
     * @return string
     */
    public function upload(UploadedFile $file, string $directory): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($directory, $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    /**
     * Retourne le path du dossier Media
     * @return string
     */
    public function getMediaDirectory() : string
    {
        return $this->parameterBag->get('app_path_media');
    }

    /**
     * Retourne le path du dossier avatar
     * @return string
     */
    public function getAvatarDirectory() : string
    {
        return $this->parameterBag->get('app_path_media_avatars');
    }

    /**
     * retourne l'url du dossier avatar pour l'affichage
     * @return string
     */
    public function getAvatarPath() : string
    {
        return $this->parameterBag->get('app_path_media_avatars_asset');
    }
}
