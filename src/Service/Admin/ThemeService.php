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
use Symfony\Component\Filesystem\Filesystem;
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

    const RELATIVE_PATH_CONFIG = 'config';
    const RELATIVE_PATH_CONFIG_FILE = self::RELATIVE_PATH_CONFIG . DIRECTORY_SEPARATOR . 'config.yaml';
    const RELATIVE_PATH_ASSET = 'assets';
    const RELATIVE_PATH_ASSET_JS = self::RELATIVE_PATH_ASSET . DIRECTORY_SEPARATOR . 'js';
    const RELATIVE_PATH_ASSET_CSS = self::RELATIVE_PATH_ASSET . DIRECTORY_SEPARATOR . 'css';


    /**
     * Tableau qui centralise les dossiers obligatoires que le thème doit avoir
     * @var array|false[]
     */
    private array $tabFolderExpected = [
        self::RELATIVE_PATH_ASSET => false,
        self::RELATIVE_PATH_ASSET_CSS => false,
        self::RELATIVE_PATH_ASSET_JS => false,
        'config' => false,
        'views' => false,
        'views' . DIRECTORY_SEPARATOR . 'include' => false,
        'views' . DIRECTORY_SEPARATOR . 'modules' => false,
        'views' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'blog' => false,
        'views' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'faq' => false,
    ];

    /**
     * Tableau qui centralise les champs attendus dans le fichier de config
     * @var array|false[]
     */
    private array $tabFieldConfigExpected = [
        self::CONFIG_KEY_NAME => ['can_empty' => false, 'success' => false],
        self::CONFIG_KEY_APP_VERSION => ['can_empty' => false, 'success' => false],
        self::CONFIG_KEY_SRC_IMG => ['can_empty' => true, 'success' => false],
        self::CONFIG_KEY_VERSION => ['can_empty' => false, 'success' => false],
        self::CONFIG_KEY_FOLDER_REF => ['can_empty' => false, 'success' => false],
        self::CONFIG_KEY_DESCRIPTION => ['can_empty' => true, 'success' => false],
        self::CONFIG_KEY_CREATOR => ['can_empty' => true, 'success' => false],
    ];

    /**
     * Tableau qui centralise les fichiers obligatoires que le thème doit avoir
     * @var string
     */
    private array $tabFileExpected = [
        self::RELATIVE_PATH_CONFIG_FILE => false,
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
        return $this->parameterBag->get('app_path_template_theme');
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
     * Retourne le path du dossier js pour les themes
     */
    public function getThemeAssetJSDirectory(): string
    {
        return $this->parameterBag->get('app_path_js_theme');
    }

    /**
     * Retourne le path du dossier css pour les themes
     * @return string
     */
    public function getThemeAssetCSSDirectory(): string
    {
        return $this->parameterBag->get('app_path_css_theme');
    }

    /**
     * Retourne le path du dossier theme public
     * @return string
     */
    public function getThemePublicDirectory(): string
    {
        return $this->parameterBag->get('app_path_theme');
    }

    /**
     * Permet d'installer un nouveau Thème
     * @param string $themeFolderName
     * @param string $realName
     * @return array
     */
    public function installNewTheme(string $themeFolderName, string $realName): array
    {
        $filesystem = new Filesystem();

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

            if (isset($this->tabFolderExpected[$dir->getRelativePathname()])) {
                $this->tabFolderExpected[$dir->getRelativePathname()] = true;
            }

            if (isset($this->tabFileExpected[$dir->getRelativePathname()])) {
                $this->tabFileExpected[$dir->getRelativePathname()] = true;
            }
        }

        $stop_install = false;
        foreach ($this->tabFolderExpected as $key => $data) {
            if ($data === false) {
                $tabReturn['msg']['errors'][] = $this->translator->trans('admin_theme#Un dossier obligatoire est manquant') . ' : <b>' . $key . '</b>';
                $stop_install = true;
            }
        }

        foreach ($this->tabFileExpected as $key => $data) {
            if ($data === false) {
                $tabReturn['msg']['errors'][] = $this->translator->trans('admin_theme#un fichier obligatoire est manquant') . ' : <b>' . $key . '</b>';
                $stop_install = true;
            }
        }

        // Si un fichier manquant on stop l'installation ici
        if ($stop_install) {
            $filesystem->remove($this->getThemeTmpDirectory() . DIRECTORY_SEPARATOR . $themeFolderName);
            $filesystem->remove($this->getThemeTmpDirectory() . DIRECTORY_SEPARATOR . $folderZipOpen);
            return $tabReturn;
        }

        // Vérification du fichier de config
        $pathFileConfig = $this->getThemeTmpDirectory() . DIRECTORY_SEPARATOR . $folderZipOpen . DIRECTORY_SEPARATOR . $theme_name . DIRECTORY_SEPARATOR . self::RELATIVE_PATH_CONFIG_FILE;
        $config = Yaml::parseFile($pathFileConfig);

        $stop_install = false;
        foreach ($config as $key => $value) {
            if (isset($this->tabFieldConfigExpected[$key])) {

                if ($this->tabFieldConfigExpected[$key]['can_empty'] === false && empty($value)) {
                    $tabReturn['msg']['errors'][] = $this->translator->trans('admin_theme#le champ suivante ne doit pas être vide') . ' : <b>' . $key . '</b>';
                    $stop_install = true;
                }
                if ($this->tabFieldConfigExpected[$key]['can_empty'] === true && empty($value)) {
                    $tabReturn['msg']['warning'][] = $this->translator->trans('admin_theme#le champ suivante est vide') . ' : <b>' . $key . '</b>';
                }
            }
        }
        // Vérification si le nom déclaré dans le champ dossier_ref est bien celui du dossier principal du theme
        if ($config[self::CONFIG_KEY_FOLDER_REF] != $theme_name) {
            $tabReturn['msg']['errors'][] = $this->translator->trans('admin_theme#le nom du dossier de référence déclaré dans folder_ref ne correspond pas au dossier racine du thème');
            $stop_install = true;
        }

        // Si une erreur est présente dans le fichier de config on stop l'installation ici
        if ($stop_install) {
            $tabReturn['success'] = false;
            $filesystem->remove($this->getThemeTmpDirectory() . DIRECTORY_SEPARATOR . $themeFolderName);
            //$filesystem->remove($this->getThemeTmpDirectory() . DIRECTORY_SEPARATOR . $folderZipOpen);
            return $tabReturn;
        }

        $folder_ref = $config[self::CONFIG_KEY_FOLDER_REF];
        $img = $config[self::CONFIG_KEY_SRC_IMG];

        // Si ici s'est que tout est ok, on déplace les fichiers

        // On déplace tout dans le dossier template\theme
        $filesystem->mirror($this->getThemeTmpDirectory() . DIRECTORY_SEPARATOR . $folderZipOpen, $this->getThemeDirectory());

        // Déplacement des assets
        $filesystem->mkdir($this->getThemeAssetCSSDirectory() . DIRECTORY_SEPARATOR . $folder_ref);
        $filesystem->mirror($this->getThemeDirectory() . DIRECTORY_SEPARATOR . $folder_ref . DIRECTORY_SEPARATOR . self::RELATIVE_PATH_ASSET_CSS, $this->getThemeAssetCSSDirectory() . DIRECTORY_SEPARATOR . $folder_ref);

        $filesystem->mkdir($this->getThemeAssetJSDirectory() . DIRECTORY_SEPARATOR . $folder_ref);
        $filesystem->mirror($this->getThemeDirectory() . DIRECTORY_SEPARATOR . $folder_ref . DIRECTORY_SEPARATOR . self::RELATIVE_PATH_ASSET_JS, $this->getThemeAssetJSDirectory() . DIRECTORY_SEPARATOR . $folder_ref);

        // Déplacement de l'image de présentation
        if (!empty($img)) {
            $filesystem->mkdir($this->getThemePublicDirectory() . DIRECTORY_SEPARATOR . $folder_ref);
            $filesystem->copy($this->getThemeDirectory() . DIRECTORY_SEPARATOR . $folder_ref . DIRECTORY_SEPARATOR . self::RELATIVE_PATH_CONFIG . DIRECTORY_SEPARATOR . $img,
                $this->getThemePublicDirectory() . DIRECTORY_SEPARATOR . $folder_ref . DIRECTORY_SEPARATOR . $img, true);
        }


        // Tout est fini on vide le dossier TMP
        $filesystem->remove($this->getThemeTmpDirectory() . DIRECTORY_SEPARATOR . $themeFolderName);
        $filesystem->remove($this->getThemeTmpDirectory() . DIRECTORY_SEPARATOR . $folderZipOpen);
        $tabReturn['success'] = true;
        return $tabReturn;


        /*$filesystem = new Filesystem();
        $filesystem->mirror($this->getThemeTmpDirectory() . '/' . 'open-zip/theme-upload', $this->getThemeDirectory());*/

    }
}