<?php
/**
 * Code associé au control des rôles dans l'application
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Service\Admin\System
 */

namespace App\Service\Admin\System;

use App\Entity\User;
use App\Service\AppService;

class AccessService extends AppService
{
    const KEY_SESSION_LISTE_ROUTE_ACCESS = 'list-route-access';

    /**
     * Permet de vérifier si l'utilisateur à les droits pour la route courante
     * Si oui retourne true sinon false;
     * @param string|null $route
     * @return bool
     */
    public function isGranted(string $route = null): bool
    {

        /*
         * TODO comprendre pourquoi cette route pose problème quand on passe dedans avec
         * ensuite [$user = $this->security->getUser()] qui semble poser problème avec l'update
         * Solution, stocker les roles en session pour éviter de faire appel à geUser();
         */

        if($route == 'admin_route_ajax_update')
        {
            return true;
        }

        /** @var User $user */
        $user = $this->security->getUser();
        if ($user == null || str_contains($route, 'front_') === true || $route == null) {
            return true;
        }

        $tabRoute = $this->request->getSession()->get(self::KEY_SESSION_LISTE_ROUTE_ACCESS);
        foreach ($tabRoute as $r)
        {
            if ($route === $r) {
                return true;
            }
        }

        /*foreach ($user->getRolesCms() as $rolesCm) {

            foreach ($rolesCm->getRouteRights() as $routeRight) {
                if ($route === $routeRight->getRoute()->getRoute()) {
                    return true;
                }
            }
        }*/
        return false;
    }
}