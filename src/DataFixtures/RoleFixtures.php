<?php
/**
 * Fixture pour les roles
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\DataFixtures
 */
namespace App\DataFixtures;

use App\Entity\Admin\Role;
use App\Entity\Admin\Route;
use App\Entity\Admin\RouteRight;
use App\Repository\Admin\RouteRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RoleFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $role = new Role();
        $role->setName('Root');
        $role->setShortLabel('ROOT');
        $role->setColor('#8F0505');
        $role->setLabel('Role système');
        $role->setCanUpdate(true);

        /** @var RouteRepository $routeRepo */
        $routeRepo = $manager->getRepository(Route::class);
        $listeRoutes = $routeRepo->getListeOrderBy();
        foreach($listeRoutes as $route)
        {
            $routeRight = new RouteRight();
            $routeRight->setRole($role);
            $routeRight->setRoute($route);
            $routeRight->setCanRead(true)->setCanEdit(true)->setCanDelete(true);
            $manager->persist($routeRight);
            $role->addRouteRight($routeRight);
        }

        $manager->persist($role);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            OptionFixtures::class,
            RouteFixtures::class,
        ];
    }
}
