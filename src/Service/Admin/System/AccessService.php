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
    /**
     * Permet de vérifier si l'utilisateur à les droits pour la route courante
     * Si oui retourne true sinon false;
     * @param string|null $route
     * @return bool
     */
    public function isGranted(string $route = null): bool
    {
        /** @var User $user */
        $user = $this->security->getUser();

        if ($user == null || str_contains($route, 'front_') === true) {
            return true;
        }

        foreach ($user->getRolesCms() as $rolesCm) {

            foreach ($rolesCm->getRouteRights() as $routeRight) {
                if ($route === $routeRight->getRoute()->getRoute()) {
                    return true;
                }
            }
        }
        return false;
    }
}