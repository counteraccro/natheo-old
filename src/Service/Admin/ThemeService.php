<?php
/**
 * Code en relation avec les thèmes
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Service\Admin
 */

namespace App\Service\Admin;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class ThemeService extends \App\Service\AppService
{
    /**
     * Permet de lire et mettre à jour en base de données les thèmes
     */
    public function readThemes()
    {
        $pathThemes = $this->getThemeDirectory();

        $finder = new Finder();
        //$themes = $finder->directories()->path($pathThemes);

        foreach ($finder->directories()->depth('== 0')->in($pathThemes) as $theme) {
            $finderConfig = new Finder();
            foreach($finderConfig->files()->in($theme->getPathname())->name('config.yaml') as $configFile)
            {
                $config = Yaml::parseFile($configFile->getPathname());
                var_dump($config);
            }

        }

        //$finder->files()->path($pathThemes . '/config')->name('config.yaml');

    }

    /**
     * Path du dossier Themes
     * @return string
     */
    public function getThemeDirectory(): string
    {
        return $this->parameterBag->get('app_path_theme');
    }
}