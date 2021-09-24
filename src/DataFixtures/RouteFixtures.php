<?php

namespace App\DataFixtures;

use App\Service\Admin\System\RouteService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RouteFixtures extends Fixture
{

    /**
     * @var RouteService
     */
    private RouteService $routeService;

    public function __construct(RouteService $routeService)
    {
        $this->routeService = $routeService;
    }

    public function load(ObjectManager $manager)
    {
        $this->routeService->updateRoutes();
    }
}
