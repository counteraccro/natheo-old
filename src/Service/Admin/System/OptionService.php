<?php
/**
 * Code associé au options de l'applications
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Service\Admin\GlobalFunction
 */
namespace App\Service\Admin\System;

use App\Entity\Admin\Option;
use App\Service\AppService;

class OptionService extends AppService
{

    const GO_GLOBAL_ELEMENT_PAR_PAGE = 'GO_GLOBAL_ELEMENT_PAR_PAGE';

    /**
     * Permet de récupérer une option en fonction de sa clée
     * Si $onlyValue est à true, renvoi uniquement la valeur de l'option, sinon renvoi l'objet
     * @param string $key
     * @param bool $onlyValue
     * @return string|Option|null
     */
    public function getOptionByKey(string $key, bool $onlyValue = false): string|Option|null
    {
        $optionRepo = $this->doctrine->getRepository(Option::class);

        /** @var Option $option */
        $option = $optionRepo->findOneBy(['name' => $key]);

        if($onlyValue)
        {
            return $option->getValue();
        }
        return $option;

    }
}