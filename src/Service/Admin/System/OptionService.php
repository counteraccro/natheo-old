<?php
/**
 * Code associé aux options de l'application
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Service\Admin\GlobalFunction
 */
namespace App\Service\Admin\System;

use App\Entity\Admin\Option;
use App\Service\AppService;

class OptionService extends AppService
{

    const GO_ADM_GLOBAL_ELEMENT_PAR_PAGE = 'GO_ADM_GLOBAL_ELEMENT_PAR_PAGE';
    const GO_ADM_GLOBAL_ELEMENT_PAR_PAGE_DEFAULT_VALUE = 20;

    /**
     * Permet de récupérer une option en fonction de sa clé. <br />
     * Si $onlyValue est à true, renvoi uniquement la valeur de l'option, sinon renvoi l'objet. <br />
     * Si l'option n'existe pas, elle est créée de façon automatique en fonction de la clé
     * @param string $key
     * @param string $default_value
     * @param bool $onlyValue
     * @return string|Option
     */
    public function getOptionByKey(string $key, string $default_value = '', bool $onlyValue = false): string|Option
    {
        $optionRepo = $this->doctrine->getRepository(Option::class);

        /** @var Option $option */
        $option = $optionRepo->findOneBy(['name' => $key]);

        if($option == null)
        {
            $option = new Option();
            $option->setName($key);
            $option->setValue($default_value);
            $this->doctrine->getManager()->persist($option);
            $this->doctrine->getManager()->flush($option);
        }

        if($onlyValue)
        {
            return $option->getValue();
        }
        return $option;
    }
}