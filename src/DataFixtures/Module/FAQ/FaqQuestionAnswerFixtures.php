<?php

namespace App\DataFixtures\Module\FAQ;

use App\DataFixtures\AppFixtures;
use App\DataFixtures\TagFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\Modules\FAQ\FaqQuestionAnswer;
use App\Entity\Modules\FAQ\FaqQuestionAnswerTag;
use App\Entity\Modules\FAQ\FaqQuestionAnswerTranslation;
use App\Entity\Modules\Tag;
use App\Entity\User;
use App\Service\Module\FAQ\FaqService;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class FaqQuestionAnswerFixtures extends AppFixtures implements DependentFixtureInterface
{
    /**
     * @var ObjectManager
     */
    private  ObjectManager $manager;

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $questionAnswer = new FaqQuestionAnswer();
        $questionAnswer->setPosition(1)->setCreateOn(new \DateTime())->setStatus(FaqService::STATUS_PUBLISHED);

        $pageTitle = 'qu-est-ce-que-natheocms';
        $questionAnswer->setFaqCategory($this->getReference(FaqCategoryFixtures::FAQ_CAT_NATHEO_REF));

        $question = "qu'est ce que NatheoCMS ?";
        $response = "NatheoCMS est un CMS gratuit développé en PHP";
        $questionAnswer = $this->addTranslation($question, $response, $pageTitle, $questionAnswer);
        $manager->persist($questionAnswer);
        $this->addTag($questionAnswer, $this->getReference(TagFixtures::TAG_NATHEO_REF), $this->getReference(UserFixtures::USER_ROOT_REF));

        // ------

        $questionAnswer = new FaqQuestionAnswer();
        $questionAnswer->setPosition(2)->setCreateOn(new \DateTime())->setStatus(FaqService::STATUS_PUBLISHED);

        $pageTitle = 'natheocms-est-il-gratuit';
        $questionAnswer->setFaqCategory($this->getReference(FaqCategoryFixtures::FAQ_CAT_NATHEO_REF));

        $question = "NatheoCMS est-il gratuit ?";
        $response = "Oui NatheoCMS est gratuit";
        $questionAnswer = $this->addTranslation($question, $response, $pageTitle, $questionAnswer);
        $manager->persist($questionAnswer);
        $this->addTag($questionAnswer, $this->getReference(TagFixtures::TAG_NATHEO_REF), $this->getReference(UserFixtures::USER_ROOT_REF));

        // ------

        $questionAnswer = new FaqQuestionAnswer();
        $questionAnswer->setPosition(1)->setCreateOn(new \DateTime())->setStatus(FaqService::STATUS_PUBLISHED);

        $pageTitle = 'question-test';
        $questionAnswer->setFaqCategory($this->getReference(FaqCategoryFixtures::FAQ_CAT_DEMO_REF));

        $question = "Une question test";
        $response = "Une réponse test";
        $questionAnswer = $this->addTranslation($question, $response, $pageTitle, $questionAnswer);
        $manager->persist($questionAnswer);
        $this->addTag($questionAnswer, $this->getReference(TagFixtures::TAG_FUN_REF), $this->getReference(UserFixtures::USER_ROOT_REF));

        $manager->flush();
    }

    /**
     * Ajoute la traduction
     * @param string $question
     * @param string $answer
     * @param string $pageTitle
     * @param FaqQuestionAnswer $faqQuestionAnswer
     * @return FaqQuestionAnswer
     */
    private function addTranslation(string $question, string $answer, string $pageTitle, FaqQuestionAnswer $faqQuestionAnswer): FaqQuestionAnswer
    {
        $locales = $this->translationService->getLocales();

        foreach($locales as $locale)
        {
            if($locale != 'fr')
            {
                $answer_l = '__' . $answer;
                $question_l = '__' . $question;
                $pageTitle_l = $pageTitle . '-' . $locale;
            }
            else {
                $answer_l = $answer;
                $question_l = $question;
                $pageTitle_l = $pageTitle;
            }

            $questionAnswerTranslation = new FaqQuestionAnswerTranslation();
            $questionAnswerTranslation->setAnswer($answer_l);
            $questionAnswerTranslation->setQuestion($question_l);
            $questionAnswerTranslation->setLanguage($locale);
            $questionAnswerTranslation->setSlug($this->slugger->slug($pageTitle_l));
            $questionAnswerTranslation->setPageTitle($pageTitle_l);
            $questionAnswerTranslation->setFaqQuestionAnswer($faqQuestionAnswer);
            $questionAnswerTranslation->setMetaDescription($locale . '-' . $pageTitle_l);
            $questionAnswerTranslation->setMetaKeyword($locale);
            $this->manager->persist($questionAnswerTranslation);
            $faqQuestionAnswer->addFaqQuestionAnswerTranslation($questionAnswerTranslation);
        }
        return $faqQuestionAnswer;
    }

    /**
     * Associe un tag
     * @param FaqQuestionAnswer $faqQuestionAnswer
     * @param Tag $tag
     * @param User $user
     */
    private function addTag(FaqQuestionAnswer $faqQuestionAnswer, Tag $tag, User $user)
    {
        $faqQuestionAnswerTag = new FaqQuestionAnswerTag();
        $faqQuestionAnswerTag->setFaqQuestionAnswer($faqQuestionAnswer);
        $faqQuestionAnswerTag->setTag($tag);
        $faqQuestionAnswerTag->setUser($user);
        $faqQuestionAnswerTag->setCreateOn(new \DateTime());
        $this->manager->persist($faqQuestionAnswerTag);
    }

    public function getDependencies(): array
    {
        return [
            TagFixtures::class,
            UserFixtures::class
        ];
    }
}
