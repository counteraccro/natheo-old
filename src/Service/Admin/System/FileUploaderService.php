<?php
/**
 * Gère les upload sur l'applications
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Service\Admin\System
 */
namespace App\Service\Admin\System;

use App\Service\AppService;
use Symfony\Component\Finder\Finder;
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
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->getClientOriginalExtension();

        try {
            $file->move($directory, $fileName);
        } catch (FileException $e) {
           echo $e->getMessage();
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

    /**
     * Retourne le path du dossier médiathèque
     * @return string
     */
    public function getMediathequeDirectory() : string
    {
        return $this->parameterBag->get('app_path_media_mediatheque');
    }

    /**
     * retourne l'url du dossier médiathèque pour l'affichage
     * @return string
     */
    public function getMediathequePath() : string
    {
        return $this->parameterBag->get('app_path_media_mediatheque_asset');
    }

    /**
     * retourne l'url du dossier des images par default de la médiathèque pour l'affichage
     * @return string
     */
    public function getMediathequeDefaultPath() : string
    {
        return $this->parameterBag->get('app_path_media_mediatheque_default_asset');
    }

    /**
     * Retourne le path du dossier theme où sont stockés les .zip avant traitement
     * @return string
     */
    public function getThemeTmpDirectory() : string
    {
        return $this->parameterBag->get('app_path_media_theme_tmp');
    }

    /**
     * Retourne le path du dossier theme dans le template
     * @return string
     */
    public function getThemeTemplateDirectory() : string
    {
        return $this->parameterBag->get('app_path_theme');
    }
}
