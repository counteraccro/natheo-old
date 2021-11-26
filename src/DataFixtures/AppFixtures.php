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
use Symfony\Component\String\Slugger\SluggerInterface;

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

    /**
     * @var SluggerInterface
     */
    protected SluggerInterface $slugger;

    public function __construct(ParameterBagInterface $parameterBag, TranslationService $translationService, SluggerInterface $slugger)
    {
        $this->parameterBag = $parameterBag;
        $this->translationService = $translationService;
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
