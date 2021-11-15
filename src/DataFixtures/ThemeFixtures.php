<?php

namespace App\DataFixtures;

use App\Service\Admin\ThemeService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ThemeFixtures extends AppFixtures
{

    /**
     * @var ThemeService
     */
    private ThemeService $themeService;

    public function __construct(ThemeService $themeService)
    {
        $this->themeService = $themeService;
    }

    public function load(ObjectManager $manager): void
    {
        $this->themeService->readThemes();
        $manager->flush();
    }
}
