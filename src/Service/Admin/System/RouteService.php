<?php
/**
 * Gestion des routes de l'application
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Service\Admin\System
 */

namespace App\Service\Admin\System;

use App\Entity\Admin\Route;
use App\Repository\Admin\RouteRepository;
use App\Service\AppService;
use Symfony\Component\Yaml\Yaml;

class RouteService extends AppService
{
    /**
     * Création / Mise à jour des routes de l'application
     */
    public function updateRoutes()
    {
        /** @var RouteRepository $routeRepo */
        $routeRepo = $this->doctrine->getManager()->getRepository(Route::class);
        $routeRepo->updateIsdepreciateAllRoute();


        $tabRouteModule = Yaml::parseFile($this->parameterBag->get('app_path_routes_modules'));

        foreach($this->router->getRouteCollection()->all() as $key => $_route)
        {
            $explode = explode('_', $key);
            if($explode[0] == '')
            {
                continue;
            }
            $module = 'error';
            if(isset($tabRouteModule['routes_modules'][$explode[1]][$this->local]))
            {
                $module = $tabRouteModule['routes_modules'][$explode[1]][$this->local];
            }

            /** @var Route $route */
            $route = $routeRepo->findOneBy(['route' => $key]);
            if($route == null)
            {
                $route = new Route();
                $route->setRoute($key);
            }
            $route->setModule($module);
            $route->setIsDepreciate(false);
            $this->doctrine->getManager()->persist($route);
        }
        $this->doctrine->getManager()->flush();
    }
}