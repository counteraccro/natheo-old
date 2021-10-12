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
use Symfony\Contracts\Translation\TranslatorInterface;

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
     * @param TranslationService $translationService
     */
    public function __construct(Doctrine $doctrine, RouterInterface $router, RequestStack $request, ParameterBagInterface $parameterBag, KernelInterface $kernel
        , TranslationService             $translationService, TranslatorInterface $translator)
    {
        parent::__construct($doctrine, $router, $request, $parameterBag, $kernel, $translator);
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

        foreach ($this->router->getRouteCollection()->all() as $key => $_route) {
            $explode = explode('_', $key);
            if ($explode[0] == '') {
                continue;
            }
            $module = $explode[1];
            if (isset($tabRouteModule['routes_modules'][$explode[1]])) {
                $module = $tabRouteModule['routes_modules'][$explode[1]];
            }

            /** @var Route $route */
            $route = $routeRepo->findOneBy(['route' => $key]);
            if ($route == null) {
                $route = new Route();
                $route->setRoute($key);
            }
            $route->setLabel($this->generateLabel($key));
            $route->setModule($module);
            $route->setIsDepreciate(false);
            $this->doctrine->getManager()->persist($route);
        }
        $this->doctrine->getManager()->flush();
    }

    /**
     * Permet de définir un label en fonction de la route
     * @param string $route
     * @return string
     */
    private function generateLabel(string $route): string
    {
        $tabRouteModule = Yaml::parseFile($this->parameterBag->get('app_path_translate_modules'))['routes_modules'];
        $tab = explode('_', $route);

        if ($tab[0] == 'front') {
            return $this->translator->trans("admin_system#Action pour le front");
        }

        $module = $this->translator->trans($tabRouteModule[$tab[1]]);

        return match ($tab[2]) {
            "index" => $this->translator->trans("admin_system#Point d'entrée du module {module}", ['{module}' => $module]),
            "ajax" => match ($tab[3]) {
                "save" => $this->translator->trans("admin_system#Appel Ajax pour sauvegarder une donnée de type {data}", ['{data}' => $module]),
                "update" => $this->translator->trans("admin_system#Appel Ajax pour mettre à jour une donnée de type {data}", ['{data}' => $module]),
                "listing" => $this->translator->trans("admin_system#Appel Ajax pour afficher la liste de {data}", ['{data}' => $module]),
                "delete" => $this->translator->trans("admin_system#Appel Ajax pour supprimer une donnée de type {data}", ['{data}' => $module]),
                "purge" => $this->translator->trans("admin_system#Appel Ajax pour purger les données de type {data}", ['{data}' => $module]),
                "reset" => $this->translator->trans("admin_system#Appel Ajax pour réinitialiser les données de type {data}", ['{data}' => $module]),
                "reload" => $this->translator->trans("admin_system#Appel Ajax pour recharger les données de type {data}", ['{data}' => $module]),
                "check" => $this->translator->trans("admin_system#Appel Ajax pour checker les données de type {data}", ['{data}' => $module]),
                default => $this->translator->trans("admin_system#Appel Ajax sans description"),
            },
            "add" => $this->translator->trans("admin_system#Ajoute une donnée de type {data}", ['{data}' => $module]),
            "edit" => $this->translator->trans("admin_system#Met à jour une donnée de type {data}", ['{data}' => $module]),
            default => $this->translator->trans("admin_system#Route sans description"),
        };
    }
}