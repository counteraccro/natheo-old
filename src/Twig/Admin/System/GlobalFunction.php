<?php

namespace App\Twig\Admin\System;

use App\Twig\AppExtension;
use Twig\Extension\RuntimeExtensionInterface;

class GlobalFunction extends AppExtension implements RuntimeExtensionInterface
{
    /**
     * @param string $value
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
}