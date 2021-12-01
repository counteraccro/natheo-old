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
            $metaDescription = 'FAQ NatheoCMS - Réponse à toutes vos questions';
            $metaKeyWord = 'FAQ';

            if($locale != 'fr')
            {
                $title = '__' . $locale . '_' . $title;
                $description = '__' . $description;
                $metaDescription = '__' . $metaDescription;
                $metaKeyWord = $metaKeyWord . ' ' . $locale;
            }

            $faqCatTranslation = new FaqCategoryTranslation();
            $faqCatTranslation->setDescription($description);
            $faqCatTranslation->setTitle($title);
            $faqCatTranslation->setLanguage($locale);
            $faqCatTranslation->setFaqCategory($faqCat);
            $faqCatTranslation->setSlug($this->slugger->slug($title));
            $faqCatTranslation->setPageTitle($title);
            $faqCatTranslation->setMetaDescription($metaDescription);
            $faqCatTranslation->setMetaKeyword($metaKeyWord);
            $manager->persist($faqCatTranslation);
            $faqCat->addFaqCategoryTranslation($faqCatTranslation);
        }
        $manager->persist($faqCat);
        $this->addReference(self::FAQ_CAT_NATHEO_REF, $faqCat);

        $faqCat = new FaqCategory();
        $faqCat->setCreateOn(new \DateTime())->setPosition(2);

        foreach($locales as $locale)
        {
            $title = 'Catégorie Exemple';
            $description = 'Ceci est un texte démo';

            if($locale != 'fr')
            {
                $title = '__' . $locale . '_' . $title;
                $description = '__' . $description;
            }

            $faqCatTranslation = new FaqCategoryTranslation();
            $faqCatTranslation->setDescription($description);
            $faqCatTranslation->setTitle($title);
            $faqCatTranslation->setLanguage($locale);
            $faqCatTranslation->setFaqCategory($faqCat);
            $faqCatTranslation->setSlug($this->slugger->slug($title));
            $faqCatTranslation->setPageTitle($title);
            $manager->persist($faqCatTranslation);
            $faqCat->addFaqCategoryTranslation($faqCatTranslation);
        }
        $manager->persist($faqCat);
        $this->addReference(self::FAQ_CAT_DEMO_REF, $faqCat);

        $faqCat = new FaqCategory();
        $faqCat->setCreateOn(new \DateTime())->setPosition(3);

        foreach($locales as $locale)
        {
            $title = 'Catégorie Démo2';
            $description = 'Ceci est un texte démo2';

            if($locale != 'fr')
            {
                $title = '__' . $locale . '_' . $title;
                $description = '__' . $description;
            }

            $faqCatTranslation = new FaqCategoryTranslation();
            $faqCatTranslation->setDescription($description);
            $faqCatTranslation->setTitle($title);
            $faqCatTranslation->setLanguage($locale);
            $faqCatTranslation->setFaqCategory($faqCat);
            $faqCatTranslation->setSlug($this->slugger->slug($title));
            $faqCatTranslation->setPageTitle($title);
            $manager->persist($faqCatTranslation);
            $faqCat->addFaqCategoryTranslation($faqCatTranslation);
        }
        $manager->persist($faqCat);
        $manager->flush();
    }
}
