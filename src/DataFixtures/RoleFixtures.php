<?php
/**
 * Fixture pour les roles
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\DataFixtures
 */
namespace App\DataFixtures;

use App\Entity\Admin\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $role = new Role();
        //$role->


        // $manager->persist($product);

        $manager->flush();
    }
}
