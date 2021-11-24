<?php

namespace App\Twig\Admin;

use Twig\Extension\RuntimeExtensionInterface;

class TranslationTwig extends AppExtension implements RuntimeExtensionInterface
{

    /**
     * Génère le formulaire de recherche pour les traductions
     */
    public function formSearchTranslation()
    {
        $listeApp = $this->translationService->getApplications();
        $listeModules = $this->translationService->getModules();
        $listeLangues = $this->translationService->getLocales();


        $html = '<div class="row-cols-auto">
                    <div class="alert alert-danger" id="translation-error-langue" style="display: none">' . $this->translator->trans('admin_translation#Vous devez cocher au moins une langue pour effectuer une recherche') . '</div>
                </div>
                <div class="col-4">';
        $html .= $this->generateSelect($this->translator->trans('admin_translation#Application'), $listeApp, 'application');
        $html .= '<br >';
        $html .= $this->generateSelect($this->translator->trans('admin_translation#Module'), $listeModules, 'module');
        $html .= '</div>
        <div class="col-4">';
        $html .= $this->generateCheckbox($this->translator->trans('admin_translation#Langue'), $listeLangues);
        $html .= '</div>
        <div class="col-4">';
        $html .= $this->generateInputText($this->translator->trans('admin_translation#Rechercher par clé'), 'key', $this->translator->trans('admin_translation#Rechercher un traduction par sa clée'));
        $html .= '<br >';
        $html .= $this->generateInputText($this->translator->trans('admin_translation#Rechercher par label'), 'label', $this->translator->trans('admin_translation#Rechercher un traduction par son label'));
        $html .= '</div>';

        return $html;
    }


    /**
     * Génère un input de type texte
     * @param string $label
     * @param string $name
     * @return string
     */
    public function generateInputText(string $label, string $name, string $placeholder): string
    {
        $id = mt_rand();
        return ' <label for="' . $id . '" class="form-label">' . $label . '</label>
                  <input type="text" name="' . $name . '" class="form-control" id="' . $id . '" placeholder="' . $placeholder . '">';
    }

    /**
     * Génère une liste de checkbox
     * @param string $label
     * @param array $liste
     * @return string
     */
    public function generateCheckbox(string $label, array $liste): string
    {
        $current = $this->translator->getLocale();
        $html = '<label for="inputState" class="form-label">' . $label . '</label>';
        foreach ($liste as $element) {
            $checked = '';
            if ($current == $element) {
                $checked = 'checked';
            }

            $id = mt_rand();
            $html .= '
                        <div class="form-check form-switch">
                            <input class="form-check-input" name="translation_' . $element . '" type="checkbox" id="' . $id . '" ' . $checked . '>
                            <label class="form-check-label" for="' . $id . '">' . $this->translator->trans('admin_translation#' . $element) . '</label>
                        </div>';
        }

        return $html;
    }

    /**
     * Génère une liste déroulante
     * @param string $label
     * @param array $liste
     * @param string $name
     * @return string
     */
    private function generateSelect(string $label, array $liste, string $name): string
    {
        $translationModule = $this->translationService->getTranslationModule();
        $for = mt_rand();

        $html = '<label for="' . $for . '" class="form-label">' . $label . '</label>
                            <select name="' . $name . '" id="' . $for . '" class="form-select">
                                <option value="">' . $this->translator->trans('admin_translation#Tout') . '</option>';
        foreach ($liste as $element) {

            $optionLabel = $element;
            if (isset($translationModule[$element])) {
                $optionLabel = $translationModule[$element];
            }

            $html .= '<option value="' . $element . '">' . $this->translator->trans($optionLabel) . '</option>';
        }

        $html .= '</select>';
        return $html;
    }
}