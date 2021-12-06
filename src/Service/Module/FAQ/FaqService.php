<?php
/**
 * Gestion de la FAQ
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Service\Module\FAQ
 */
namespace App\Service\Module\FAQ;

use App\Entity\Modules\FAQ\FaqCategory;
use App\Entity\Modules\FAQ\FaqQuestionAnswer;
use App\Service\AppService;

class FaqService extends AppService
{
    const STATUS_PUBLISHED = 1;
    const STATUS_HIDDEN = 2;

    /**
     * Retourne la liste des ordres pour la FAQCategory
     */
    public function getListeOrderFaqCategory()
    {
        $currentLocal = $this->request->getLocale();
        return $this->doctrine->getRepository(FaqCategory::class)->getListePosition($currentLocal, $this->translator->trans('admin_faq#Avant'));
    }

    /**
     * Met à jour l'ensemble des positions des FaqCategory en fonction de la FaqCategory en paramètre et de
     * son ancienne position
     * @param FaqCategory $faqCategory
     * @param int $oldPosition
     */
    public function updatePositionFaqCategory(FaqCategory $faqCategory, int $oldPosition)
    {
        $faqRep = $this->doctrine->getRepository(FaqCategory::class);

        $newPosition = $faqCategory->getPosition();

        if ($faqCategory->getPosition() < $oldPosition) {
            $action = "up";
        } else {
            $action = "down";
        }
        $result = $faqRep->getListeAfterPosition($oldPosition, $action);

        /** @var FaqCategory $faqCatUpdate */
        foreach ($result as $faqCatUpdate) {
            if ($faqCatUpdate->getId() === $faqCategory->getId()) {
                continue;
            }

            if ($faqCategory->getPosition() < $oldPosition) {
                if ($faqCatUpdate->getPosition() >= $newPosition) {
                    $faqCatUpdate->setPosition($faqCatUpdate->getPosition() + 1);
                }
            } else {

                if ($faqCatUpdate->getPosition() <= $newPosition) {
                    $faqCatUpdate->setPosition($faqCatUpdate->getPosition() - 1);
                }
            }

            $this->doctrine->getManager()->persist($faqCatUpdate);
        }
        $this->doctrine->getManager()->flush();
    }

    /**
     * Met à jour l'ensemble des positions des FaqQuestionAnswer en fonction de sa FaqCategory et de
     * son ancienne position
     * @param FaqQuestionAnswer $faqQuestionAnswer
     * @param int $oldPosition
     */
    public function updatePositionFaqQuestionAnswer(FaqQuestionAnswer $faqQuestionAnswer, int $oldPosition)
    {
        /*$faqRep = $this->doctrine->getRepository(FaqCategory::class);

        $newPosition = $faqCategory->getPosition();

        if ($faqCategory->getPosition() < $oldPosition) {
            $action = "up";
        } else {
            $action = "down";
        }
        $result = $faqRep->getListeAfterPosition($oldPosition, $action);

        /** @var FaqCategory $faqCatUpdate */
        /*foreach ($result as $faqCatUpdate) {
            if ($faqCatUpdate->getId() === $faqCategory->getId()) {
                continue;
            }

            if ($faqCategory->getPosition() < $oldPosition) {
                if ($faqCatUpdate->getPosition() >= $newPosition) {
                    $faqCatUpdate->setPosition($faqCatUpdate->getPosition() + 1);
                }
            } else {

                if ($faqCatUpdate->getPosition() <= $newPosition) {
                    $faqCatUpdate->setPosition($faqCatUpdate->getPosition() - 1);
                }
            }

            $this->doctrine->getManager()->persist($faqCatUpdate);
        }
        $this->doctrine->getManager()->flush();*/
    }
}