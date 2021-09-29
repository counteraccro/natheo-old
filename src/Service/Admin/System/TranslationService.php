<?php
/**
 * Service regroupant les fonctions sur les traductions de l'application
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Service\Admin\GlobalFunction
 */

namespace App\Service\Admin\System;

use App\Entity\Admin\TranslationKey;
use App\Entity\Admin\TranslationLabel;
use App\Repository\Admin\TranslationKeyRepository;
use App\Service\AppService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class TranslationService extends AppService
{

    /**
     * Met à jour les traductions depuis la base de données vers les fichiers yaml
     * Attention cette action écrase l'ensemble des données présent dans les fichier yaml
     */
    public function updateTranslateFromBDDtoYamlFile()
    {
        $path_translation = $this->parameterBag->get('app_path_translation');
        $locales = $this->getLocales();
        $tranlationKeyRepo = $this->doctrine->getRepository(TranslationKey::class);

        $finder = new Finder();
        $finder->name('messages+intl-icu*');
        foreach ($finder->files()->in($path_translation) as $file) {
            unlink($file->getPathname());
        }

        $tabGlobalTrans = [];
        $allTranslation = $tranlationKeyRepo->findAll();
        foreach ($locales as $locale) {
            /** @var TranslationKey $translationKey */
            foreach ($allTranslation as $translationKey) {
                foreach ($translationKey->getTranslationLabels() as $translationLabel) {
                    if ($translationLabel->getLanguage() == $locale) {
                        $tabGlobalTrans[$locale][$translationKey->getName()] = $translationLabel->getLabel();
                    }
                }
            }
        }

        foreach ($tabGlobalTrans as $locale => $tabTrans) {
            $yaml = Yaml::dump($tabTrans);
            file_put_contents($path_translation . '/messages+intl-icu.' . $locale . '.yaml', $yaml);
        }

        // On vide le cache applicatif
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'cache:clear'
        ]);
        $output = new NullOutput();
        $application->run($input, $output);
    }

    /**
     * Met à jour la base de données à partir des fichiers de traductions
     */
    public function updateTranslateFromYamlFileToBDD()
    {
        $path_translation = $this->parameterBag->get('app_path_translation');
        $path_config = $this->parameterBag->get('app_path_config_cms');
        $tabLocales = $this->getLocales();

        $finder = new Finder();
        $finder->name('config-translate.yaml');
        foreach ($finder->files()->in($path_config) as $file) {
            $this->createUpdateTranslationByYamlFile($file, $tabLocales);
        }

        $ref_local = $tabLocales[0];
        $finder = new Finder();
        $finder->name('messages+intl-icu.' . $ref_local . '.yaml');
        foreach ($finder->files()->in($path_translation) as $file) {
            $this->createUpdateTranslationByYamlFile($file, $tabLocales);
        }
    }

    /**
     * Génère l'ensemble des translations via la commande translation:update
     * Attention cette fonction va remettre à jour l'ensemble des fichiers depuis le code applicatif et non la base de données
     * Perde de données traduite possible
     * @throws Exception
     */
    public function generateTranslationByCommande()
    {
        $tabLocales = $this->getLocales();

        foreach ($tabLocales as $locale) {
            $application = new Application($this->kernel);
            $application->setAutoExit(false);

            $input = new ArrayInput([
                'command' => 'translation:update',
                // (optional) define the value of command arguments
                'locale' => $locale,
                // (optional) pass options to the command
                '--force' => true,
                '--format' => 'yaml'
            ]);
            // You can use NullOutput() if you don't need the output
            $output = new NullOutput();
            $application->run($input, $output);
        }
    }


    /**
     * Permet de créer ou mettre à jour une traduction à partir d'un fichier yaml
     * @param mixed $file
     */
    private function createUpdateTranslationByYamlFile(mixed $file, array $locales)
    {
        $content = Yaml::parseFile($file->getRealPath());
        $translationKeyRepo = $this->doctrine->getRepository(TranslationKey::class);

        foreach ($content as $key => $label) {
            $application = $module = $label = '';
            $tmp = explode('#', $key);
            if (count($tmp) > 1) {
                $info = explode('_', $tmp[0]);
                $application = $info[0];
                unset($info[0]);
                foreach ($info as $char) {
                    $module .= $char . '_';
                }
                $module = substr($module, 0, -1);
                $label = $tmp[1];
            }

            $transKey = $translationKeyRepo->findBy(['name' => $key]);
            if ($transKey == null) {
                $translationKey = new TranslationKey();
                $translationKey->setName($key);
                $translationKey->setApplication($application);
                $translationKey->setModule($module);

                foreach ($locales as $locale) {
                    $translationLabel = new TranslationLabel();
                    $translationLabel->setLanguage($locale);

                    $__ = '__';
                    if ($locale == 'fr') {
                        $__ = '';
                    }

                    $translationLabel->setLabel($__ . $label);
                    $translationLabel->setTranslationKey($translationKey);
                    $this->doctrine->getManager()->persist($translationLabel);
                }

                $this->doctrine->getManager()->persist($translationKey);
            }
            else {

            }
        }
        $this->doctrine->getManager()->flush();
    }

    /**
     * Retourne un tableau des locales du site
     * @return array
     */
    public function getLocales(): array
    {
        $locales = $this->parameterBag->get('app_locales');
        return explode('|', $locales);
    }

    /**
     * Retourne la liste des applications des traductions
     */
    public function getApplications(): array
    {
        /** @var TranslationKeyRepository $translationKeyRepo */
        $translationKeyRepo = $this->doctrine->getRepository(TranslationKey::class);
        $result = $translationKeyRepo->listeApplications();

        $return = [];
        foreach($result as $value)
        {
            $return[] = $value['application'];
        }
        return $return;
    }

    /**
     * Retourne la liste des modules des traductions
     */
    public function getModules(): array
    {
        /** @var TranslationKeyRepository $translationKeyRepo */
        $translationKeyRepo = $this->doctrine->getRepository(TranslationKey::class);
        $result = $translationKeyRepo->listeModules();

        $return = [];
        foreach($result as $value)
        {
            $return[] = $value['module'];
        }
        return $return;
    }

    /**
     * Permet de récupérer le tableau de traduction des modules
     * @return array
     */
    public function getTranslationModule() : array {

        return Yaml::parseFile($this->parameterBag->get('app_path_translate_modules'))['routes_modules'];
    }
}