<?php

namespace App\Service\Module\FAQ;

use App\Entity\Modules\FAQ\FaqCategory;
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
        return $this->doctrine->getRepository(FaqCategory::class)->getListeOrder($currentLocal);
    }
}