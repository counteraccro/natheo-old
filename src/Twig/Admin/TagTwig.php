<?php

namespace App\Twig\Admin;

use Twig\Extension\RuntimeExtensionInterface;

class TagTwig extends AppExtension implements RuntimeExtensionInterface
{
    /**
     * Permet de générer le code HTML et JS pour selection un ou plusieurs tags à associer à une entité
     */
    public function selectTagForEntity(): string
    {
        $html = '<label for="tag-list" class="form-label">' . $this->translator->trans('admin_faq#Tags') . '</label>
                    <div class="input-group mb-3">
                        <input class="form-control" data-url="' . $this->router->generate('admin_tag_ajax_search') . '" list="tag-list-options" id="tag-list" data-new="' . $this->translator->trans('admin_faq#Ajouter le tag') . '" placeholder="' . $this->translator->trans("admin_faq#Recherche un tag") . '">
                        <button class="btn btn-primary" data-url="' . $this->router->generate(('admin_tag_ajax_tmp_add')) . '" data-loading="' . $this->translator->trans('admin_tag#Chargement de la modale pour ajouter un tag') . '" type="button" id="btn-modal-add-tag">' . $this->translator->trans('admin_faq#Nouveau tag') . '</button>
                        <datalist id="tag-list-options"></datalist>
                    </div>

                    <fieldset class="border p-2">
                        <legend class="float-none" style="width:inherit; padding:0 10px; border-bottom:none; font-size: 1em;">' . $this->translator->trans("admin_faq#Tags ajoutés") . '</legend>
                        <div id="tag-list-content" data-url="' . $this->router->generate('admin_tag_ajax_tmp_read') . '"></div>
                    </fieldset>';

        $html .= $this->generateJSTagForEntity("#tag-list");
        return $html;
    }


    /**
     * Génère le code JS pour la fonction selectTagForEntity()
     * @param string $id
     * @return string
     */
    private function generateJSTagForEntity(string $id): string
    {
        $url_save = $this->router->generate('admin_tag_ajax_tmp_save', ['id' => 0]);

        return "<script>
        $(document).ready(function () {
            Tag.Launch();
            Tag.SelectTagForElement('" . $id . "', '" . $url_save . "');
        });
        </script>";
    }
}