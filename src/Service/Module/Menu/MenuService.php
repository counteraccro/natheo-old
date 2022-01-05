<?php
/**
 * Gestion des menus
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Service\Module
 */
namespace App\Service\Module\Menu;

use App\Entity\Modules\Menu\Menu;
use App\Entity\Modules\Menu\MenuElement;
use App\Service\AppService;

class MenuService extends AppService
{

    const SESSION_KEY_MENU_ID = 'session_menu_id';
    const SESSION_KEY_ELEMENT_MENU = 'session_element_menu';

    /**
     * Retourne la liste des menus elements trier par parents / enfants
     * @return array
     */
    public function getListeMenuElementOrderByParentChildren(): array
    {
        $idMenu = $this->request->getSession()->get(MenuService::SESSION_KEY_MENU_ID);
        if($idMenu != 0)
        {
            /** @var Menu $menu */
            $menu = $this->getMenuById($idMenu);
            $tabMenuElement = $menu->getMenuElements();
        }
        else {
            $tabMenuElement = $this->request->getSession()->get(self::SESSION_KEY_ELEMENT_MENU);
        }

        $tabReturn = [];

        if(empty($tabMenuElement))
        {
            return $tabReturn;
        }

        /** @var MenuElement $menuElement */
        foreach($tabMenuElement as $menuElement)
        {
            if($menuElement->getParent() == null)
            {
                $tabReturn = $this->treeMenuElement($menuElement, $tabReturn, 0);
            }
        }
        return $tabReturn;
    }

    /**
     * @param MenuElement $menuElement
     * @param array $tabReturn
     * @param int $deep
     * @return array
     */
    private function treeMenuElement(MenuElement $menuElement, array $tabReturn, int $deep = 0): array
    {
        $tabulation = '';
        for ($i = 1; $i <= $deep; $i++) {
            $tabulation .= "-";
        }
        if($tabulation != "")
        {
            $tabulation .= '>';
        }

        $tabReturn[$tabulation . ' ' . $menuElement->getLabel()] = $menuElement->getId();
        $deep = $deep +1;

        //var_dump($menuElement->getChildren());
        foreach($menuElement->getChildren() as $child)
        {
            $tabReturn = $this->treeMenuElement($child, $tabReturn, $deep);
        }

        return $tabReturn;
    }

    /**
     * Retourne un menu en fonction de son id
     * @param int $id
     * @return Menu
     */
    public function getMenuById(int $id): Menu
    {
        return $this->doctrine->getRepository(Menu::class)->findOneBy(['id' => $id]);
    }
}