<?php

namespace App\Twig\Admin\System;

use App\Twig\AppExtension;
use Twig\Extension\RuntimeExtensionInterface;

/**
 * Génération du fil d'ariane pour l'administration
 */
class Breadcrumb extends AppExtension implements RuntimeExtensionInterface
{
    /**
     * Point d'entrée pour la génération du fil d'arianne
     * @param array $elements
     * @return string
     */
    public function htmlRender(array $elements): string
    {
       $html = '<nav aria-label="breadcrumb">
              <ol class="breadcrumb">';

        $array = array_keys($elements);
        $last_key = end($array);
       foreach($elements as $label => $route)
       {
           if($label == $last_key)
           {
               $html .= '<li class="breadcrumb-item active" aria-current="page">'. $label . '</li>';
           }
           else {
               $html .=  '<li class="breadcrumb-item"><a href="' . $this->urlGenerator->generate($route) . '">' . $label . '</a></li>';
           }
       }
        $html .= '</ol>
            </nav>';
       return $html;
    }
}