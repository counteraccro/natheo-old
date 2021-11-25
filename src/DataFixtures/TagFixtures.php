<?php

namespace App\DataFixtures;

use App\Entity\Modules\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TagFixtures extends Fixture
{
    const TAG_NATHEO_REF = 'tag-natheo-ref';
    const TAG_FUN_REF = 'tag-fun-ref';

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

        $this->addReference(self::TAG_NATHEO_REF, $tag);
        $this->addReference(self::TAG_FUN_REF, $tag3);
    }
}
