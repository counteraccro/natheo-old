<?php
/**
 * Gestion de la pagination
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Twig\Admin\GlobalFunction
 */

namespace App\Twig\Admin\System;

use App\Twig\AppExtension;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Twig\Extension\RuntimeExtensionInterface;

class Paginate extends AppExtension implements RuntimeExtensionInterface
{
    /**
     * Point d'entrée pour la pagination
     * @param Paginator $paginator
     * @param int $page
     * @param int $limit
     * @param string $route
     * @param string $global_id
     * @return string
     */
    public function htmlRender(Paginator $paginator, int $page, int $limit, string $route, string $global_id): string
    {
        $html = $previous = $next = '';
        $maxPages = ceil($paginator->count() / $limit);

        if ($page == 1) {
            $previous = 'disabled';
        }
        if ($page == $maxPages) {
            $next = 'disabled';
        }

        $html .= '<nav aria-label="' . $this->translator->trans('admin_pagination#Bock pagination') . '">
                <ul class="pagination justify-content-end" data-id="' . $global_id . '" data-loading="' . $this->translator->trans('admin_pagination#Chargement des données...') . '">
                     <li class="page-item disabled me-2">
                        <a class="page-link" href="#">' . $paginator->count() . ' ' . $this->translator->trans('admin_pagination#Elements') . '</a>
                    </li>
                     <li class="page-item disabled me-2">
                        <a class="page-link" href="#">' . $limit . ' ' . $this->translator->trans('admin_pagination#Par page') . '</a>
                    </li>
                    <li class="page-item ' . $previous . '">
                        <a class="page-link" href="' . $this->urlGenerator->generate($route, ['page' => ($page - 1)]) . '" tabindex="-1" aria-disabled="true">' . $this->translator->trans('admin_pagination#Précédent') . '</a>
                    </li>';

        $maxI = ($page + 2);
        if ($maxI > $maxPages) {
            $maxI = $maxPages;
        }

        $minI = ($page - 2);
        if ($minI < 1) {
            $minI = 1;
        }

        for ($i = $minI; $i <= $maxI; $i++) {
            $current = '';

            if ($page == $i) {
                $current = 'active';
            }

            $html .= '<li class="page-item ' . $current . '">
                <a class="page-link" href="' . $this->urlGenerator->generate($route, ['page' => $i]) . '">' . $i . '</a>
               </li>';
        }

        $html .= '<li class="page-item ' . $next . '">
                        <a class="page-link" href="' . $this->urlGenerator->generate($route, ['page' => ($page + 1)]) . '">' . $this->translator->trans('admin_pagination#Suivant') . '</a>
                    </li>
                </ul>
            </nav>';

        $html .= $this->generateJs();

        return $html;
    }

    /**
     * Génère le code JS pour la pagination
     */
    private function generateJs(): string
    {
        return '<script>
            $( document ).ready(function() {
                System.Paginate();
            });
        </script>';
    }
}