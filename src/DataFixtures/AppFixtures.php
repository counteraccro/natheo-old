<?php
/**
 * Fixture générique
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\DataFixtures
 */
namespace App\DataFixtures;

use App\Service\Admin\System\TranslationService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AppFixtures extends Fixture
{
    /**
     * @var ParameterBagInterface
     */
    protected ParameterBagInterface $parameterBag;

    /**
     * @var TranslationService
     */
    protected TranslationService $translationService;

    public function __construct(ParameterBagInterface $parameterBag, TranslationService $translationService)
    {
        $this->parameterBag = $parameterBag;
        $this->translationService = $translationService;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
