<?php

namespace App\Service\Admin;

use App\Entity\Admin\Role;
use App\Entity\Admin\Route;
use App\Entity\Admin\RouteRight;
use App\Repository\Admin\RouteRepository;
use App\Service\AppService;

class RoleService extends AppService
{
    const ROOT_NAME = 'root';
    const ROOT_SHORT_LABEL = 'ROOT';
    const ROOT_COLOR = '#8F0505';

    /**
     * Mise à jour des droits sur les roles uniquement sur ceux avec can_update à true
     */
    public function RoleRouteRightUpdate()
    {
        $roleRepo = $this->doctrine->getRepository(Role::class);
        /** @var RouteRepository $routeRepo */
        $routeRepo = $this->doctrine->getRepository(Route::class);
        $listeRoles = $roleRepo->findBy(['can_update' => true]);

        /** @var Role $role */
        foreach($listeRoles as $role)
        {
            $tab = $this->RouteRightGroupByModule($role->getRouteRights()->toArray());

            foreach($tab as $module => $nbRoute)
            {
                $listeRoutes = $routeRepo->findBy(['module' => $module, 'is_depreciate' => false]);
                if($nbRoute !== count($listeRoutes))
                {
                    foreach($listeRoutes as $route)
                    {
                        foreach($role->getRouteRights() as $key => $routeRight)
                        {
                            if($routeRight->getRoute()->getId() == $route->getId())
                            {
                                $role->getRouteRights()->removeElement($routeRight);
                            }
                        }

                        $routeR = new RouteRight();
                        $routeR->setRoute($route);
                        $routeR->setRole($role);
                        $routeR->setCanEdit(true)->setCanRead(true)->setCanDelete(true);
                        $role->addRouteRight($routeR);
                        $this->doctrine->getManager()->persist($routeR);
                    }
                }
            }
        }
        $this->doctrine->getManager()->flush();
    }

    /**
     * Retourne un tableau indiquant pour chaque module le nombre de routes
     */
    private function RouteRightGroupByModule(array $routeRights): array
    {
        $tab = [];

        /** @var RouteRight $routeRight */
        foreach($routeRights as $routeRight)
        {
            if(!isset($tab[$routeRight->getRoute()->getModule()]))
            {
                $tab[$routeRight->getRoute()->getModule()] = 1;
            }
            else {
                $tab[$routeRight->getRoute()->getModule()]++;
            }
        }
        return $tab;
    }
}