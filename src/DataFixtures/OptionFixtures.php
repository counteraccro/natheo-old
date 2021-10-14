<?php
/**
 * Fixture pour les options
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\DataFixtures
 */
namespace App\DataFixtures;

use App\Entity\Admin\Option;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;

class OptionFixtures extends AppFixtures
{
    public function load(ObjectManager $manager)
    {

        $globalOptions = Yaml::parseFile($this->parameterBag->get('app_path_global_options'));

        foreach($globalOptions['global_options'] as $options)
        {
            foreach($options as $key => $option)
            {
                $optionEntity = new Option();
                $optionEntity->setName($key);
                $optionEntity->setValue($option[\App\Twig\Admin\OptionTwig::KEY_DEFAULT]);
                $manager->persist($optionEntity);
            }
        }
        $manager->flush();
    }
}
