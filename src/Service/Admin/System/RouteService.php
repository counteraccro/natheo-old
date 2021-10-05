<?php
/**
 * Gestion des routes de l'application
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Service\Admin\GlobalFunction
 */

namespace App\Service\Admin\System;

use App\Entity\Admin\Route;
use App\Repository\Admin\RouteRepository;
use App\Service\AppService;
use Doctrine\Persistence\ManagerRegistry as Doctrine;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Yaml\Yaml;

class RouteService extends AppService
{
    /**
     * @var TranslationService
     */
    protected TranslationService $translationService;

    /**
     * @param Doctrine $doctrine
     * @param RouterInterface $router
     * @param RequestStack $request
     * @param ParameterBagInterface $parameterBag
     * @param KernelInterface $kernel
     */
    public function __construct(Doctrine $doctrine, RouterInterface $router, RequestStack $request, ParameterBagInterface $parameterBag, KernelInterface $kernel
        , TranslationService $translationService)
    {
        parent::__construct($doctrine, $router, $request, $parameterBag, $kernel);
        $this->translationService = $translationService;
    }

    /**
     * Création / Mise à jour des routes de l'application
     */
    public function updateRoutes()
    {
        /** @var RouteRepository $routeRepo */
        $routeRepo = $this->doctrine->getManager()->getRepository(Route::class);
        $routeRepo->updateIsdepreciateAllRoute();


        $tabRouteModule = Yaml::parseFile($this->parameterBag->get('app_path_translate_modules'));

        foreach($this->router->getRouteCollection()->all() as $key => $_route)
        {
            $explode = explode('_', $key);
            if($explode[0] == '')
            {
                continue;
            }
            $module = $explode[1];
            if(isset($tabRouteModule['routes_modules'][$explode[1]]))
            {
                $module = $tabRouteModule['routes_modules'][$explode[1]];
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