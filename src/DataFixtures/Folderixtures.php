<?php

namespace App\DataFixtures;

use App\Entity\Media\Folder;
use Doctrine\Persistence\ObjectManager;

class Folderixtures extends AppFixtures
{
    public function load(ObjectManager $manager): void
    {
        $folder = new Folder();
        $folder->setName('Articles');
        $manager->persist($folder);

        $folder2 = new Folder();
        $folder2->setName('Site');

        $folder3 = new Folder();
        $folder3->setName('Page évènements');
        $folder3->setParent($folder2);
        $folder2->addChild($folder3);

        $manager->persist($folder2);
        $manager->persist($folder3);

        $manager->flush();
    }
}
