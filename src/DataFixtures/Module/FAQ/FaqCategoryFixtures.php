<?php

namespace App\DataFixtures\Module\FAQ;

use App\DataFixtures\AppFixtures;
use App\Entity\Modules\FAQ\FaqCategory;
use App\Entity\Modules\FAQ\FaqCategoryTranslation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FaqCategoryFixtures extends AppFixtures
{
    const FAQ_CAT_NATHEO_REF = 'faq-cat-natheo';
    const FAQ_CAT_DEMO_REF = 'faq-cat-demo-ref';

    public function load(ObjectManager $manager): void
    {
        $faqCat = new FaqCategory();
        $faqCat->setCreateOn(new \DateTime())->setPosition(1);

        $locales = $this->translationService->getLocales();

        foreach($locales as $locale)
        {
            $title = 'CMS NatheoCMS';
            $description = 'Toutes vos questions sur le CMS NatheoCMS';

            if($locale != 'fr')
            {
                $title = '__' . $title;
                $description = '__' . $description;
            }

            $faqCatTranslation = new FaqCategoryTranslation();
            $faqCatTranslation->setDescription($description);
            $faqCatTranslation->setTitle($title);
            $faqCatTranslation->setLanguage($locale);
            $faqCatTranslation->setFaqCategory($faqCat);
            $manager->persist($faqCatTranslation);
            $faqCat->addFaqCategoryTranslation($faqCatTranslation);
        }
        $manager->persist($faqCat);
        $this->addReference(self::FAQ_CAT_NATHEO_REF, $faqCat);

        $faqCat = new FaqCategory();
        $faqCat->setCreateOn(new \DateTime())->setPosition(2);

        foreach($locales as $locale)
        {
            $title = 'Catégorie Démo';
            $description = 'Ceci est un texte démo';

            if($locale != 'fr')
            {
                $title = '__' . $title;
                $description = '__' . $description;
            }

            $faqCatTranslation = new FaqCategoryTranslation();
            $faqCatTranslation->setDescription($description);
            $faqCatTranslation->setTitle($title);
            $faqCatTranslation->setLanguage($locale);
            $faqCatTranslation->setFaqCategory($faqCat);
            $manager->persist($faqCatTranslation);
            $faqCat->addFaqCategoryTranslation($faqCatTranslation);
        }
        $manager->persist($faqCat);
        $manager->flush();
        $this->addReference(self::FAQ_CAT_DEMO_REF, $faqCat);
    }
}
