<?php
/**
 * Gère les fichiers sur l'application
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Service\Admin\System
 */
namespace App\Service\Admin\System;

use App\Service\AppService;
use Symfony\Component\Finder\Finder;

class FileService extends AppService
{
    /**
     * Supprime l'image envoyée en paramètre
     * @param string $delete_file
     * @param string $path
     * @return bool
     */
    public function delete(string $delete_file, string $path) {
        $finder = new Finder();
        $finder->files()->in($path);
        foreach($finder as $file)
        {
           if($delete_file == $file->getFilename())
           {
               unlink($path . '/' . $delete_file);
               return true;
           }
        }
        return false;
    }
}