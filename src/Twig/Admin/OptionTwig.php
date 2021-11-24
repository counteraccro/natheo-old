<?php
/**
 * Génération des options du sites sur l'admin
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Twig\Admin
 */

namespace App\Twig\Admin;

use Symfony\Component\Yaml\Yaml;
use Twig\Extension\RuntimeExtensionInterface;

class OptionTwig extends AppExtension implements RuntimeExtensionInterface
{

    const GLOBAL_OPTIONS = 'global_options';
    const KEY_TYPE = 'type';
    const KEY_DEFAULT = 'default';
    const KEY_LABEL = 'label';
    const KEY_HELP = 'help';
    const TYPE_TEXT = 'text';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_TEXTAREA = 'textarea';
    const TYPE_LIST = 'list';
    const KEY_VAL_SPECIAL = 'special';
    const KEY_REQUIRE = 'require';
    const KEY_MSG_ERROR = 'msg_error';

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
               $html .= match ($option[self::KEY_TYPE]) {
                   self::TYPE_TEXT => $this->inputText($key, $option),
                   self::TYPE_BOOLEAN => $this->checkBox($key, $option),
                   self::TYPE_TEXTAREA => $this->textarea($key, $option),
                   self::TYPE_LIST => $this->select($key, $option),
                   default => ''
               };
           }

            $html .= '</div></div>';
        }

        return $html;
    }

    /**
     * Permet de générer un type select
     * @param string $key
     * @param array $option
     * @return string
     */
    private function select(string $key, array $option) : string
    {
        $value = $this->optionService->getOptionByKey($key, $option[self::KEY_DEFAULT], true);
        $html = '<div class="mb-3">
        <label for="' . $key . '" class="form-label">' . $this->translator->trans($option[self::KEY_LABEL]) . '</label>
        <select class="form-select input-option" aria-label="Default select example"  id="' . $key . '">';

        $tab = explode('|', $option['list_value']);
        foreach ($tab as $element) {

            $tab2 = explode(':', $element);
            $itemVal = $tab2[0];
            $item = $tab2[1];


            if ($itemVal == self::KEY_VAL_SPECIAL)
            {
               switch ($item)
               {
                   case 'app_locales' :
                       $html .= $this->generateListLocal($value);
                       break;
                   case 'time_format' :
                       $html .= $this->generateListeTimeFormat($value);
                       break;
                   case 'date_format' :
                        $html .= $this->generateListeDateFormat($value);
                       break;
                   case 'date_short_format' :
                       $html .= $this->generateListeDateShortFormat($value);
                       break;
                   default :
                       break;
               }
            }
            else {
                $select = '';
                if($value == $itemVal)
                {
                    $select = 'selected';
                }
                $html .= '<option value="' . $itemVal . '" ' . $select . '>' . $this->translator->trans($item) . '</option>';
            }
        }


        $html .= '</select>';
        $html .= $this->addHelp($option);
        $html .= '</div>';

        return $html;
    }

    /**
     * Permet de générer une liste de format de date
     * @param string $value
     * @return string
     */
    private function generateListeDateShortFormat(string $value) : string
    {
        $date = new \DateTime();
        $html = '';
        $tabFormat = ['%e/%m/%G', '%e-%m-%G', '%e.%m.%G',
            '%d/%m/%G', '%d-%m-%G', '%d.%m.%G',
            '%m/%d/%G', '%m-%d-%G', '%m.%d.%G',
            '%e/%m/%g', '%e-%m-%g', '%e.%m.%g',
            '%d/%m/%g', '%d-%m-%g', '%d.%m.%g',
            '%m/%d/%g', '%m-%d-%g', '%m.%d.%g'];

        foreach($tabFormat as $format)
        {
            $select = '';
            if($value == $format)
            {
                $select = 'selected';
            }

            $html .= '<option value="' . $format . '" ' . $select . '>' . $this->dateService->getDateFormatTranslate($date, $format) . '</option>';
        }

        return $html;
    }

    /**
     * Permet de générer une liste de format de date
     * @param string $value
     * @return string
     */
    private function generateListeDateFormat(string $value) : string
    {
        $date = new \DateTime();
        $html = '';
        $tabFormat = ['%e %B %Y', '%a %e %B %Y', '%A %e %B %Y',
                '%e %B, %Y', '%a %e %B, %Y', '%A %e %B, %Y',
                '%d %B, %Y', '%d %B %Y',
                '%B %d %Y',
                '%a %B %e %Y', '%A %B %e %Y',
                '%a %B %d, %Y', '%A %B %d, %Y'];

        foreach($tabFormat as $format)
        {
            $select = '';
            if($value == $format)
            {
                $select = 'selected';
            }

            $html .= '<option value="' . $format . '" ' . $select . '>' . $this->dateService->getDateFormatTranslate($date, $format) . '</option>';
        }

        return $html;
    }

    /**
     * Permet de générer une liste de format d'heure
     * @param string $value
     * @return string
     */
    private function generateListeTimeFormat(string $value) : string
    {
        $date = new \DateTime();
        $html = '';
        $tabFormat = ['H:i', 'H:i:s', 'g:i a', 'g:i: A'];

        foreach($tabFormat as $format)
        {
            $select = '';
            if($value == $format)
            {
                $select = 'selected';
            }

            $html .= '<option value="' . $format . '" ' . $select . '>' . $date->format($format) . '</option>';
        }

        return $html;
    }

    /**
     * Génère une liste de langues
     * @param string $value
     * @return string
     */
    private function generateListLocal(string $value): string
    {
        $html = '';
        $tabLocal = $this->translationService->getLocales();

        foreach($tabLocal as $local)
        {
            $select = '';
            if($value == $local)
            {
                $select = 'selected';
            }

            $html .= '<option value="' . $local . '" ' . $select . '>' . $this->translator->trans('admin_translation#' . $local) . '</option>';
        }
        return $html;
    }

    /**
     * Permet de générer un input de type textarea
     * @param string $key
     * @param array $option
     * @return string
     */
    private function textarea(string $key, array $option): string
    {
        $value = $this->optionService->getOptionByKey($key, $option[self::KEY_DEFAULT], true);
        $require = $dataMsgError = '';
        if(isset($option[self::KEY_REQUIRE]))
        {
            $require = 'require';
            $dataMsgError = 'data-msg-error = "' . $this->translator->trans($option[self::KEY_MSG_ERROR]) . '"';
        }

        $html = '<div class="mb-3">
          <label for="' . $key . '" class="form-label">' . $this->translator->trans($option[self::KEY_LABEL]) . '</label>
          <textarea class="form-control input-option ' . $require . '" id="' . $key . '" rows="3" ' . $dataMsgError . '>
            ' . $value . '
            </textarea>';
        $html .= $this->addHelp($option);
        $html .= '</div>';

        return $html;

    }

    /**
     * Permet de générer un système de checkbox
     * @param string $key
     * @param array $option
     * @return string
     */
    private function checkBox(string $key, array $option): string
    {
        $value = $this->optionService->getOptionByKey($key, $option[self::KEY_DEFAULT], true);

        $checked = '';
        if($value == 1)
        {
            $checked = 'checked';
        }

        $html = '<div class="form-check form-switch">
          <input class="form-check-input input-option" type="checkbox" id="' . $key . '" ' . $checked . '>
          <label class="form-check-label" for="' . $key . '">' . $this->translator->trans($option[self::KEY_LABEL]) . '</label>';
        $html .= $this->addHelp($option);
        $html .= '</div>';

        return $html;
    }

    /**
     * Génération d'une option de type texte
     * @param String $key
     * @param array $option
     * @return string
     */
    private function inputText(String $key, array $option): string
    {
        $value = $this->optionService->getOptionByKey($key, $option[self::KEY_DEFAULT], true);
        $require = $dataMsgError = '';
        if(isset($option[self::KEY_REQUIRE]))
        {
            $require = 'require';
            $dataMsgError = 'data-msg-error = "' . $this->translator->trans($option[self::KEY_MSG_ERROR]) . '"';
        }

        $html = '<div class="mb-3">
                <label for="' . $key . '" class="form-label">' . $this->translator->trans($option[self::KEY_LABEL]) . '</label>
                <input type="text" class="form-control input-option ' . $require . '" ' . $dataMsgError . ' id="' . $key . '" value="' . $this->translator->trans($value). '">';

        $html .= $this->addHelp($option);
        $html .= '</div>';

        return $html;
    }

    /**
     * Ajout le code html pour afficher le help texte
     * @param array $option
     * @return string
     */
    private function addHelp(array $option): string
    {
        if(isset($option[self::KEY_HELP]))
        {
            return '<div id="emailHelp" class="form-text">' . $this->translator->trans($option[self::KEY_HELP]) . '</div>';
        }
        return '';
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