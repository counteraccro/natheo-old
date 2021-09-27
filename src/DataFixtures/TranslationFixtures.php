<?php

namespace App\DataFixtures;

use App\Service\Admin\System\TranslationService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TranslationFixtures extends Fixture
{
    /**
     * @var TranslationService
     */
    private TranslationService $transtaltionService;

    public function __construct(TranslationService $translationService)
    {
        $this->transtaltionService = $translationService;
    }

    public function load(ObjectManager $manager)
    {
        $this->transtaltionService->generateTranslationByCommande();
        $this->transtaltionService->updateTranslateFromYamlFileToBDD();
        //$manager->flush();
        //$this->transtaltionService->updateTranslateFromBDDtoYamlFile();

        // $product = new Product();
        // $manager->persist($product);

        //$manager->flush();
    }
}
