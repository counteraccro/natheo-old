<?php

namespace App\Service\Admin;

use App\Entity\User;

class UserService extends \App\Service\AppService
{
    const ROOT_NAME = 'ROOT';
    const ROOT_SURNAME = 'Super Admin';
    const GHOST_NAME = 'John';
    const GHOST_SURNAME = 'Doe';

    const DEFAULT_AVATAR = 'default\img_avatar.png';


    /**
     * Met à jour la date de dernière connexion
     * @param User $user
     */
    public function updateLastLogin(User $user)
    {
        $user->setLastLogin(new \DateTime());
        $this->doctrine->getManager()->persist($user);
        $this->doctrine->getManager()->flush();
    }
}