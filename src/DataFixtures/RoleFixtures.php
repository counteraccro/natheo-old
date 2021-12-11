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
use App\Service\Admin\RoleService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RoleFixtures extends Fixture implements DependentFixtureInterface
{
    public const FIXTURE_ROLE_ROOT_REF = 'role-root';
    public const FIXTURE_ROLE_ADM_REF = 'role-adm';

    public function load(ObjectManager $manager)
    {
        $role = new Role();
        $role->setName(RoleService::ROOT_NAME);
        $role->setShortLabel(RoleService::ROOT_SHORT_LABEL);
        $role->setColor(RoleService::ROOT_COLOR);
        $role->setLabel('Role système');
        $role->setCanUpdate(true);
        $role = $this->addRouteRight($manager, $role, []);

        $manager->persist($role);
        $this->addReference(self::FIXTURE_ROLE_ROOT_REF, $role);

        $role = new Role();
        $role->setName('Administrateur');
        $role->setShortLabel('ADM');
        $role->setColor('#866EC7');
        $role->setLabel('Administrateur');
        $role->setCanUpdate(true);
        $role = $this->addRouteRight($manager, $role, ['admin_system#System', 'admin_route#Gestion des routes', '']);

        $this->addReference(self::FIXTURE_ROLE_ADM_REF, $role);
        $manager->persist($role);
        $manager->flush();
    }

    /**
     * Ajout une liste de route à un role
     * @param ObjectManager $manager
     * @param Role $role
     * @param array $tabModule
     * @return Role
     */
    private function addRouteRight(ObjectManager $manager, Role $role, array $tabModule): Role
    {
        /** @var RouteRepository $routeRepo */
        $routeRepo = $manager->getRepository(Route::class);
        $listeRoutes = $routeRepo->getListeOrderBy();

        /** @var Route $route */
        foreach($listeRoutes as $route)
        {
            if(!empty($tabModule) && in_array($route->getModule(), $tabModule))
            {
                continue;
            }

            $routeRight = new RouteRight();
            $routeRight->setRole($role);
            $routeRight->setRoute($route);
            $routeRight->setCanRead(true)->setCanEdit(true)->setCanDelete(true);
            $manager->persist($routeRight);
            $role->addRouteRight($routeRight);
        }

        return $role;
    }

    public function getDependencies(): array
    {
        return [
            OptionFixtures::class,
            RouteFixtures::class,
        ];
    }
}
