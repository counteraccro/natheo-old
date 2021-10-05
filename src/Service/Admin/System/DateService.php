<?php

namespace App\Service\Admin\System;

use App\Service\AppService;
use DateTime;

class DateService extends AppService
{
    /**
     * Renvoi une date au format envoyé en paramètre et traduit dans la langue courante
     * @param string $format
     * @param DateTime $date
     * @return string
     */
    public function getDateFormatTranslate(string $format, DateTime $date) : string
    {
        setlocale(LC_TIME, $this->request->getLocale());
        return strftime($format, $date->getTimestamp());
    }
}