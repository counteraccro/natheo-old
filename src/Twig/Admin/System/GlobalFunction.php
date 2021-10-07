<?php

namespace App\Twig\Admin\System;

use App\Twig\AppExtension;
use Twig\Extension\RuntimeExtensionInterface;

class GlobalFunction extends AppExtension implements RuntimeExtensionInterface
{
    /**
     * Permet de générer un champ caché pour l'édition directement depuis un tableau
     * @param string $value
     * @param string $url
     * @return string
     */
    public function generateHiddenInput(string $value, string $url): string
    {
        $id = mt_rand();
        $html = '<span class="span-input-hidden">
                    <span class="txt-input-switch" id="label-' . $id . '">' . $value . '</span>
                    <span class="float-end text-success" id="success-label-' . $id . '" style="display: none"><i class="fa fa-check"></i> ' . $this->translator->trans('admin_system#Mise à jour réussi') . '</span>
                     <span id="input-label-' . $id . '" style="display: none">
                            <textarea class="form-control">' . $value . '</textarea>
                            <div class="btn btn-sm btn-primary btn-submit-input-switch mt-1" data-id="label-' . $id . '" data-url="' . $url . '"><i class="fa fa-check-circle"></i> ' . $this->translator->trans("admin_system#Valider") . '</div>
                            <div class="btn btn-sm btn-secondary btn-reset-input-switch mt-1 ms-1" data-id="label-' . $id . '"><i class="fa fa-undo"></i> ' . $this->translator->trans("admin_system#Annuler") . '</div>
                     </span>
                 </span>';
        return $html;
    }

    /**
     * Permet de générer un champ de recherche générique pour une recherche simple
     * @param array $fields
     * @param string $divId
     * @return string
     */
    public function generateSearchInput(array $fields, string $divId): string
    {
        $id = mt_rand();

        $html = '<div class="input-group" id="' . $id . '">
                  <input type="text" class="form-control" aria-label="Text input with segmented dropdown button" placeholder="' . $this->translator->trans('admin_system#Recherche...') . '">
                  <button type="button" class="btn btn-primary btn-search" data-id="' . $divId . '" data-value="reset" data-reset="' . $this->translator->trans('admin_system#Rechercher') . '" data-text="' . $this->translator->trans('admin_system#Rechercher sur le champ ') . '">' . $this->translator->trans('admin_system#Rechercher') . '</button>
                  <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="visually-hidden">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end">';

        foreach ($fields as $field => $label) {
            $html .= '<li><a class="dropdown-item" href="#" data-value="' . $field . '">' . $this->translator->trans($label) . '</a></li>';
        }

        $html .= '
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#" data-value="reset">' . $this->translator->trans('admin_system#Annuler la recherche') . '</a></li>
                  </ul>
                </div>';

        $html .= '<script>
        $( document ).ready(function() {
            System.EventSearch("#' . $id . '")
        });
    </script>';

        return $html;
    }
}