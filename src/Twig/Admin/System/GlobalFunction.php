<?php

namespace App\Twig\Admin\System;

use App\Twig\AppExtension;
use Twig\Extension\RuntimeExtensionInterface;

class GlobalFunction extends AppExtension implements RuntimeExtensionInterface
{
    /**
     * @param string $value
     */
    public function generateHiddenInput(string $value): string
    {
        $id = mt_rand();
        $html = '<span class="txt-input-switch" id="label-' . $id . '">' . $value . '</span>
                                <span id="input-label-' . $id . '" style="display: none">
                                    <textarea class="form-control">' . $value . '</textarea>
                                </span>';

        $html .= '<script>
            $( document ).ready(function() {
                System.EventHiddenInput();
            });
        </script>';

        return $html;
    }
}