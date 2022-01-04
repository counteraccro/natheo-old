<?php
/**
 * Gestion des menus
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Service\Module
 */
namespace App\Service\Module\Menu;

use App\Entity\Modules\Menu\MenuElement;
use App\Service\AppService;

class MenuService extends AppService
{

    const SESSION_KEY_MENU_ID = 'session_menu_id';
    const SESSION_KEY_ELEMENT_MENU = 'session_element_menu';

    /**
     * Retourne la liste des menus elements trier par parents / enfants
     */
    public function getListeMenuElementOrderByParentChildren(): array
    {
        $tabMenuElement = $this->request->getSession()->get(self::SESSION_KEY_ELEMENT_MENU);

        function cmp($a, $b): int
        {
            if ($a->getparent() == $b->getparent() && $a->getPosition() == $b->getPosition()) {
                return 0;
            }
            return ($a->getparent() < $b->getparent()) ? -1 : 1;
        }

        uasort($tabMenuElement, 'App\Service\Module\Menu\cmp');

       $tabReturn = [];

        /** @var MenuElement $menuElement */
        foreach($tabMenuElement as $menuElement)
       {
           $tabReturn[$menuElement->getLabel()] = $menuElement->getId();
       }
        return $tabReturn;
    }
}