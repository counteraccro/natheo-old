<?php
/**
 * Génération des options du sites sur l'admin
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Twig\Admin
 */

namespace App\Twig\Admin;

use App\Twig\AppExtension;
use Symfony\Component\Yaml\Yaml;
use Twig\Extension\RuntimeExtensionInterface;

class Option extends AppExtension implements RuntimeExtensionInterface
{

    const GLOBAL_OPTIONS = 'global_options';
    const KEY_TYPE = 'type';
    const KEY_DEFAULT = 'default';
    const KEY_LABEL = 'label';
    const KEY_HELP = 'help';
    const TYPE_TEXT = 'text';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_LIST = 'list';

    /**
     * Point d'entrée pour la génération des options
     * @param string $option_file
     */
    public function htmlRender(string $option_file)
    {
        $html = '';
        $tabGenericOptions = $this->loadYamlOptionFile($option_file);

        foreach($tabGenericOptions['global_options'] as $category => $tabOptions)
        {
            $html .= '<div class="card mb-2">
                          <div class="card-header">
                                ' . $this->translator->trans($category) . '
                          </div>
                          <div class="card-body">';

           foreach($tabOptions as $key => $option)
           {

               var_dump($option);

                switch ($option[self::KEY_TYPE])
                {
                    case self::TYPE_TEXT :
                        $html .= $this->InputText($key, $option);
                        break;
                }
           }

            $html .= '</div></div>';
        }

        return $html;
    }

    /**
     * Génération d'une option de type texte
     * @param String $key
     * @param array $option
     * @return string
     */
    private function InputText(String $key, Array $option): string
    {
        $html = '<div class="mb-3">
                <label for="' . $key . '" class="form-label">' . $this->translator->trans($option[self::KEY_LABEL]) . '</label>
                <input type="text" class="form-control" id="' . $key . '">';

        if(isset($option[self::KEY_HELP]))
        {
            $html .= '<div id="emailHelp" class="form-text">' . $this->translator->trans($option[self::KEY_HELP]) . '</div>';
        }
        $html .= '</div>';

        return $html;
    }


    /**
     * Permet de charger un fichier YAML
     * @param string $option_file
     * @return array
     */
    private function loadYamlOptionFile(string $option_file): array
    {
        return match ($option_file) {
            self::GLOBAL_OPTIONS => Yaml::parseFile($this->parameterBag->get('app_path_global_options')),
            default => array(),
        };
    }
}