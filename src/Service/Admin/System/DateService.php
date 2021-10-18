<?php
/**
 * Gère les formats de date de l'application
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Service\Admin\System
 */
namespace App\Service\Admin\System;

use App\Service\AppService;
use DateTime;

class DateService extends AppService
{
    /**
     * Renvoi une date au format envoyé en paramètre et traduit dans la langue courante
     * @param string $format
     * @param DateTime $date
     * @param string|null $timeFormat
     * @return string
     */
    public function getDateFormatTranslate(DateTime $date, string $format, string $timeFormat = null) : string
    {
        setlocale(LC_TIME, $this->request->getLocale());
        $time = '';
        if($timeFormat != null)
        {
            $time = $date->format($timeFormat);
        }
        return strftime($format, $date->getTimestamp()) . ' ' . $time;
    }
}