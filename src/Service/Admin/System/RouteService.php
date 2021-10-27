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
     * Création / Mise à jour des routes de l'application
     */
    public function updateRoutes()
    {
        /** @var RouteRepository $routeRepo */
        $routeRepo = $this->doctrine->getManager()->getRepository(Route::class);
        $routeRepo->updateIsdepreciateAllRoute();


        $tabRouteModule = $this->getTranslateModules();

        foreach ($this->router->getRouteCollection()->all() as $key => $_route) {

            $explode = explode('_', $key);

            if ($explode[0] == '') {
                continue;
            }
            $module = $explode[1];
            if (isset($tabRouteModule[$explode[1]])) {
                $module = $tabRouteModule[$explode[1]];
            }

            /** @var Route $route */
            $route = $routeRepo->findOneBy(['route' => $key]);
            if ($route === null) {

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
        $tab = explode('_', $route);

        if ($tab[0] == 'front') {
            return "admin_system#Action pour le front";
        }
        return match ($tab[2]) {
            "index" => "admin_system#Point d'entrée du module {data}",
            "ajax" => match ($tab[3]) {
                "save" => "admin_system#Appel Ajax pour sauvegarder une donnée de type {data}",
                "update" => "admin_system#Appel Ajax pour mettre à jour une donnée de type {data}",
                "listing" => "admin_system#Appel Ajax pour afficher la liste de {data}",
                "delete" => "admin_system#Appel Ajax pour supprimer une donnée de type {data}",
                "purge" => "admin_system#Appel Ajax pour purger les données de type {data}",
                "reset" => "admin_system#Appel Ajax pour réinitialiser les données de type {data}",
                "reload" => "admin_system#Appel Ajax pour recharger les données de type {data}",
                "check" => "admin_system#Appel Ajax pour checker les données de type {data}",
                "tree" =>  "admin_system#Appel Ajax pour générer un arbre de donnée de type {data}",
                "see" => match($tab[4]) {
                    "folder", "content" => "admin_system#Appel Ajax pour voir le contenu d'un dossier",
                },
                "add" => match($tab[4]) {
                    "folder" => "admin_system#Appel Ajax pour créer un dossier",
                },
                "edit" => match($tab[4]) {
                    "folder" => "admin_system#Appel Ajax pour éditer un dossier",
                },
                default => "admin_system#Appel Ajax sans description",
            },
            "add" => "admin_system#Ajoute une donnée de type {data}",
            "edit" => "admin_system#Met à jour une donnée de type {data}",
            "delete" => "admin_system#Supprime une donnée de type {data}",
            "me" => "admin_system#Edition de mes données de type {data}",
            "disabled" => "admin_system#Désactive une donnée de type {data}",
            default => "admin_system#Route sans description",
        };
    }

    /**
     * Retourne la liste des traductions pour les modules
     * @return array
     */
    public function getTranslateModules(): array
    {
        return Yaml::parseFile($this->parameterBag->get('app_path_translate_modules'))['routes_modules'];
    }
}