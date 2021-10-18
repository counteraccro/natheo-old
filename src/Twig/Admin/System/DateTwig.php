<?php
/**
 * Gestion des formats des dates dans les vues
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Twig\Admin\System
 */
namespace App\Twig\Admin\System;

use App\Twig\AppExtension;
use DateTime;
use Twig\Extension\RuntimeExtensionInterface;

class DateTwig extends AppExtension implements RuntimeExtensionInterface
{

    /**
     * Permet de formater correctement une date
     * @param DateTime $date
     * @param string $dateFormat
     * @param string|null $timeFormat
     * @return string
     */
    public function dateFormat(DateTime $date, string $dateFormat, string $timeFormat = null): string
    {
        return $this->dateService->getDateFormatTranslate($date, $dateFormat, $timeFormat);
    }
}