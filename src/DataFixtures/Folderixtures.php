<?php

namespace App\DataFixtures;

use App\Entity\Media\Folder;
use Doctrine\Persistence\ObjectManager;

class Folderixtures extends AppFixtures
{
    public function load(ObjectManager $manager): void
    {
        $folder = new Folder();
        $folder->setName('Folder 1');
        $manager->persist($folder);

        $folder2 = new Folder();
        $folder2->setName('Folder 2');

        $folder3 = new Folder();
        $folder3->setName('Sub-Folder 2');
        $folder3->setParent($folder2);
        $folder2->addChild($folder3);

        $folder4 = new Folder();
        $folder4->setName('Sub-Sub-Folder 2');
        $folder4->setParent($folder3);
        $folder3->addChild($folder4);

        $folder5 = new Folder();
        $folder5->setName('Sub-Sub-Sub-Folder 2');
        $folder5->setParent($folder4);
        $folder4->addChild($folder5);

        $folder6 = new Folder();
        $folder6->setName('Sub-Folder 2-2');
        $folder6->setParent($folder2);
        $folder2->addChild($folder6);

        $manager->persist($folder2);
        $manager->persist($folder3);
        $manager->persist($folder4);
        $manager->persist($folder5);
        $manager->persist($folder6);

        $manager->flush();
    }
}
