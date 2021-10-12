<?php
/**
 * Génération du code HTML pour l'ajout / edition d'un role
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Twig\Admin
 */

namespace App\Twig\Admin;

use App\Entity\Admin\Route;
use App\Twig\AppExtension;
use Symfony\Component\Yaml\Yaml;
use Twig\Extension\RuntimeExtensionInterface;

class Role extends AppExtension implements RuntimeExtensionInterface
{
    /**
     * Permet de générer la liste de route par module pour l'attribution des droits
     * @param array $listeRoute
     */
    public function generateRouteRight(array $listeRoute): string
    {
        $html = '<div class="accordion" id="listeRouteRight">';
        $translateModule = Yaml::parseFile($this->parameterBag->get('app_path_translate_modules'))['routes_modules'];
        $tmpModule = '';
        $array = array_keys($listeRoute);
        $last_key = end($array);

        /** @var Route $route */
        foreach($listeRoute as $key => $route)
        {
            $tmp = explode('_', $route->getRoute());
            $module = $this->translator->trans($translateModule[$tmp[1]]);

            if($tmpModule == '' || $tmpModule != $route->getModule())
            {
                $tmpModule = $route->getModule();

                $html .= '<div class="accordion-item">
                            <h2 class="accordion-header" id="heading-' . $route->getId()  . '">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordtion-' . $route->getId()  . '" aria-expanded="false" aria-controls="accordtion-' . $route->getId()  . '">
                                    ' . $this->translator->trans( $route->getModule()) . '
                                </button>
                            </h2>
                            <div id="accordtion-' . $route->getId()  . '" class="accordion-collapse collapse" aria-labelledby="heading-' . $route->getId()  . '" data-bs-parent="#accordionExample">
                                <div class="accordion-body">';
            }

            $html .= '<div class="row">
                        <div class="col-1">
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
                            </div>
                        </div>
                        <div class="col-4">' . $route->getRoute() . '</div>
                        <div class="col">' . $this->translator->trans($route->getLabel(), ['{data}' => $module]) . '</div>
                       </div>';

            if((isset($listeRoute[$key + 1]) && $listeRoute[$key + 1]->getModule() != $tmpModule) || $last_key == $key)
            {
                $html .= '  </div>
                            </div>
                        </div>';
            }
        }

        $html .= '</div>';

        return $html;
    }
}