<?php

namespace App\DataFixtures;

use App\Entity\Module\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TagFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $tag = new Tag();
        $tag->setName('NatheoCMS')->setColor('#866EC7');

        $tag2 = new Tag();
        $tag2->setName('Bug')->setColor('#C70039');

        $tag3 = new Tag();
        $tag3->setName('Fun')->setColor('#338DFF');

        $manager->persist($tag);
        $manager->persist($tag2);
        $manager->persist($tag3);

        $manager->flush();
    }
}
