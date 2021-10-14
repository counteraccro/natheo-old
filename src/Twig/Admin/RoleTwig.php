<?php
/**
 * Génération du code HTML pour l'ajout / edition d'un role
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Twig\Admin
 */

namespace App\Twig\Admin;

use App\Entity\Admin\Route;
use App\Entity\Admin\RouteRight;
use App\Twig\AppExtension;
use Symfony\Component\Yaml\Yaml;
use Twig\Extension\RuntimeExtensionInterface;

class RoleTwig extends AppExtension implements RuntimeExtensionInterface
{
    private array $requiredRoute = [
        'front_security_app_login',
        'front_security_app_logout'
    ];

    /**
     * Permet de générer la liste de route par module pour l'attribution des droits
     * @param array $listeRoute
     */
    public function generateRouteRight(array $listeRoute, \App\Entity\Admin\Role $role): string
    {
        $html = '<div class="accordion" id="listeRouteRight">';
        $translateModule = Yaml::parseFile($this->parameterBag->get('app_path_translate_modules'))['routes_modules'];
        $tmpModule = '';
        $array = array_keys($listeRoute);
        $last_key = end($array);
        $classTmp = mt_rand();

        /** @var Route $route */
        foreach($listeRoute as $key => $route)
        {
            $tmp = explode('_', $route->getRoute());
            $module = $this->translator->trans($translateModule[$tmp[1]]);

            if($tmpModule == '' || $tmpModule != $route->getModule())
            {
                $tmpModule = $route->getModule();
                $classTmp = mt_rand();

                $html .= '<div class="accordion-item">
                            <h2 class="accordion-header" id="heading-' . $route->getId()  . '">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordtion-' . $route->getId()  . '" aria-expanded="false" aria-controls="accordtion-' . $route->getId()  . '">
                                    <input class="form-check-input role-checkbox-all" type="checkbox" value="" data-class="' . $classTmp . '" id="check-all-' . $route->getId()  . '"> &nbsp;&nbsp;
                                    ' . $this->translator->trans( $route->getModule()) . '
                                </button>
                            </h2>
                            <div id="accordtion-' . $route->getId()  . '" class="accordion-collapse collapse" aria-labelledby="heading-' . $route->getId()  . '" data-bs-parent="#accordionExample">
                                <div class="accordion-body">';
            }

            $checked = '';
            if(in_array($route->getRoute(), $this->requiredRoute))
            {
                $checked = 'checked';
            }

            /** @var RouteRight $routeRight */
            foreach($role->getRouteRights() as $routeRight)
            {
                if($routeRight->getRoute()->getId() == $route->getId())
                {
                    $checked = 'checked';
                    break;
                }
            }

            $html .= '<div class="row">
                        <div class="col-1">
                            <div class="form-check">
                              <input class="form-check-input role-checkbox ' . $classTmp . '" data-route-id="' . $route->getId()  . '" type="checkbox" value="" id="check-' . $route->getId()  . '" ' . $checked . '>
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

    /**
     * Retourne une liste de module en fonction de routerights
     * @param array $routeRights
     * @return string
     */
    public function getListeModules(array $routeRights): string
    {
        $module = '';
        $html = '';
        $i = 0;

        /** @var RouteRight $routeRight */
        foreach($routeRights as $routeRight)
        {
            $route = $routeRight->getRoute();
            if($module == "" || $module != $route->getModule())
            {
                $br = '';
                if($i%3 == 2)
                {
                    $br = '<br />';
                }

                $html .= '<span class="badge bg-primary me-2">' . $this->translator->trans($route->getModule()) . '</span>' . $br;
                $module = $route->getModule();
                $i++;
            }
        }

        if($html == '')
        {
            $html = '<span class="text-danger"><i>' . $this->translator->trans('admin_role#Ce rôle n\'a aucun droit') . '</i></span>';
        }

        return $html;
    }
}