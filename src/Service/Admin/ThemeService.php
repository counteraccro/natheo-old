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
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
     * Tableau qui centralise les dossiers obligatoires que le thème doit avoir
     * @var array|false[]
     */
    private array $tabFolderExpected = [
        'asset' => false,
        'assets' . DIRECTORY_SEPARATOR . 'css' => false,
        'assets' . DIRECTORY_SEPARATOR . 'js' => false,
        'config' => false,
        'views' => false,
        'views' . DIRECTORY_SEPARATOR . 'include' => false,
        'views' . DIRECTORY_SEPARATOR . 'modules' => false,
        'views' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'blog' => false,
        'views' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'faq' => false,
    ];

    /**
     * Tableau qui centralise les fichiers obligatoires que le thème doit avoir
     * @var string
     */
    private array $tabFileExpected = [
        'config' . DIRECTORY_SEPARATOR . 'config.yaml2' => false,
    ];

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
    public function getThemeSelected(): Theme
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

    /**
     * Retourne le path du dossier theme où sont stockés les .zip avant traitement
     * @return string
     */
    public function getThemeTmpDirectory(): string
    {
        return $this->parameterBag->get('app_path_media_theme_tmp');
    }

    /**
     * Permet d'installer un nouveau Thème
     * @param string $themeFolderName
     * @param string $realName
     * @return array
     */
    public function installNewTheme(string $themeFolderName, string $realName): array
    {
        $filesystem = new \Symfony\Component\Filesystem\Filesystem();

        $tabReturn = [
            'success' => false,
            'msg' => ['errors' => [], 'warning' => []]
        ];

        // Ouverture du zip
        $zip = new \ZipArchive();
        $res = $zip->open($this->getThemeTmpDirectory() . '/' . $themeFolderName);
        if ($res === false) {
            $tabReturn['msg'] = $this->translator->trans('admin_theme#Le thème téléchargé est vide ou corrompu');
            $filesystem->remove($this->getThemeTmpDirectory() . '/' . $themeFolderName);
            return $tabReturn;
        }

        $folderZipOpen = str_replace('.zip', '', $realName);

        $zip->extractTo($this->getThemeTmpDirectory());
        $zip->close();

        $finder = new Finder();
        // Tester si la racine du zip à bien qu'un projet
        if ($finder->directories()->depth('== 0')->in($this->getThemeTmpDirectory() . '/' . $folderZipOpen)->count() > 1) {
            $tabReturn['msg']['errors'][] = $this->translator->trans('admin_theme#Plus d\'un dossier à été détecté à la racine du dossier ZIP, Arrêt de l\'installation du thème');
            $filesystem->remove($this->getThemeTmpDirectory() . '/' . $themeFolderName);
            return $tabReturn;
        }

        // Récupérer ce dossier
        $theme_dir = $finder->directories()->depth('== 0')->in($this->getThemeTmpDirectory() . '/' . $folderZipOpen)->getIterator()->current();
        /** @var SplFileInfo $theme_dir */
        $theme_name = $theme_dir->getFilename();

        $finder = new Finder();
        // Vérification si les dossiers requis sont bien présent
        /** @var SplFileInfo $dir */
        foreach ($finder->depth('< 100')->in($this->getThemeTmpDirectory() . '/' . $folderZipOpen . '/' . $theme_name)->sortByType() as $dir) {

            if(isset($this->tabFolderExpected[$dir->getRelativePathname()]))
            {
                $this->tabFolderExpected[$dir->getRelativePathname()] = true;
            }

            echo $dir->getRelativePathname() . '<br />';
            if(isset($this->tabFileExpected[$dir->getRelativePathname()]))
            {
                $this->tabFileExpected[$dir->getRelativePathname()] = true;
            }
        }

        $stop_install = false;
        foreach($this->tabFolderExpected as $key => $data)
        {
            if($data === false)
            {
                $tabReturn['msg']['errors'][] = $this->translator->trans('admin_theme#Un dossier obligatoire est manquant') . ' : <b>' . $key . '</b>';
                $stop_install = true;
            }
        }

        foreach($this->tabFileExpected as $key => $data)
        {
            if($data === false)
            {
                $tabReturn['msg']['errors'][] = $this->translator->trans('admin_theme#un fichier obligatoire est manquant') . ' : <b>' . $key . '</b>';
                $stop_install = true;
            }
        }

        // Si un fichier manquant on stop l'installation ici
        if($stop_install)
        {
            $filesystem->remove($this->getThemeTmpDirectory() . '/' . $themeFolderName);
            $filesystem->remove($this->getThemeTmpDirectory() . '/' . $folderZipOpen);
            return $tabReturn;
        }


        $tabReturn['success'] = true;
        return $tabReturn;


        /*$filesystem = new Filesystem();
        $filesystem->mirror($this->getThemeTmpDirectory() . '/' . 'open-zip/theme-upload', $this->getThemeDirectory());*/

    }
}