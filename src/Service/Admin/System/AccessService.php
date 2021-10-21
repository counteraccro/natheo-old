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
    public function isGranted(string $route = null)
    {
        /** @var User $user */
        $user = $this->security->getUser();

        if($user == null)
        {
            return true;
        }

        foreach ($user->getRolesCms() as $rolesCm) {

            //echo $rolesCm->getName() . '<br />';
            foreach ($rolesCm->getRouteRights() as $routeRight) {
                    //echo '-----' . $routeRight->getRoute()->getRoute() . '<br/>';
            }
        }
    }
}