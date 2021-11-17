<?php
/**
 * Code en relation avec les thèmes
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Service\Admin
 */

namespace App\Service\Admin;

use App\Entity\Admin\Theme;
use App\Service\AppService;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Yaml\Yaml;

class ThemeService extends AppService
{
    const CONFIG_KEY_NAME = 'name';
    const CONFIG_KEY_APP_VERSION = 'app_version';
    const CONFIG_KEY_SRC_IMG = 'src_img';
    const CONFIG_KEY_VERSION = 'version';
    const CONFIG_KEY_FOLDER_REF = 'folder_ref';
    const CONFIG_KEY_DESCRIPTION = 'description';
    const CONFIG_KEY_CREATOR = 'creator';
    const DEFAULT_THEME = 'horizon';

    /**
     * Permet de lire et mettre à jour en base de données les thèmes présent dans le dossier templates/themes
     */
    public function readThemes(): array
    {
        $pathThemes = $this->getThemeDirectory();

        $finder = new Finder();
        $theme_selected = $this->doctrine->getRepository(Theme::class)->findOneBy(['is_selected' => 1]);

        $is_selected = false;
        if ($theme_selected == null) {
            $is_selected = true;
        }

        $tabThemes = [];
        foreach ($finder->directories()->depth('== 0')->in($pathThemes) as $theme_dir) {
            $finderConfig = new Finder();
            foreach ($finderConfig->files()->in($theme_dir->getPathname())->name('config.yaml') as $configFile) {
                $config = Yaml::parseFile($configFile->getPathname());

                $theme = $this->doctrine->getRepository(Theme::class)->findOneBy(['name' => $config[self::CONFIG_KEY_NAME]]);
                if ($theme == null) {
                    $theme = new Theme();
                    $theme->setName($config[self::CONFIG_KEY_NAME]);
                    $theme->setCreateOn(new \DateTime());
                    if ($is_selected && $config[self::CONFIG_KEY_NAME] == self::DEFAULT_THEME) {
                        $theme->setIsSelected(true);
                    } else {
                        $theme->setIsSelected(false);
                    }
                }
                $theme->setAppVersion($config[self::CONFIG_KEY_APP_VERSION]);
                $theme->setFolderRef($config[self::CONFIG_KEY_FOLDER_REF]);
                $theme->setImage($config[self::CONFIG_KEY_SRC_IMG]);
                $theme->setVersion($config[self::CONFIG_KEY_VERSION]);
                $theme->setDescription($config[self::CONFIG_KEY_DESCRIPTION]);
                $theme->setCreator($config[self::CONFIG_KEY_CREATOR]);

                if (floatval($this->parameterBag->get('app_version')) <= floatval($config[self::CONFIG_KEY_APP_VERSION])) {
                    $theme->setIsDepreciate(false);
                } else {
                    $theme->setIsDepreciate(true);
                }

                $config[self::CONFIG_KEY_SRC_IMG] = '/themes/' . $config[self::CONFIG_KEY_FOLDER_REF] . '/' . $config[self::CONFIG_KEY_SRC_IMG];
                $this->doctrine->getManager()->persist($theme);
                $tabThemes[] = [
                    'config' => $config,
                    'theme' => $theme
                ];
            }
        }
        $this->doctrine->getManager()->flush();
        return $tabThemes;
    }

    /**
     * Retourne le theme selectionné
     * @return Theme
     */
    public function getThemeSelected() : Theme
    {
        return $this->doctrine->getRepository(Theme::class)->findOneBy(['is_selected' => 1]);
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