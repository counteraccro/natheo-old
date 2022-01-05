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
use Doctrine\ORM\PersistentCollection;

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
       $tabMenuElement = $this->getMenuElements();

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
     * Trie les enfants d'un menu element sous la forme d'un arbre
     * @param MenuElement $menuElement
     * @param array $tabReturn
     * @param int $deep
     * @return array
     */
    public function treeMenuElement(MenuElement $menuElement, array $tabReturn, int $deep = 0, $object = false): array
    {
        if($object)
        {
            $tabReturn[$menuElement->getId()] = ['obj' => $menuElement, 'deep' => $deep];
        }
        else {
            $tabulation = '';
            for ($i = 1; $i <= $deep; $i++) {
                $tabulation .= "-";
            }
            if($tabulation != "")
            {
                $tabulation .= '>';
            }

            $tabReturn[$tabulation . ' ' . $menuElement->getLabel()] = $menuElement->getId();
        }

        $deep = $deep +1;

        foreach($menuElement->getChildren() as $child)
        {
            $tabReturn = $this->treeMenuElement($child, $tabReturn, $deep, $object);
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

    /**
     * Retourne la liste des elements du menu courant en cours de création / modification
     * @return array
     */
    public function getMenuElements(): array
    {
        $idMenu = $this->request->getSession()->get(MenuService::SESSION_KEY_MENU_ID);
        if($idMenu != 0)
        {
            $menu = $this->getMenuById($idMenu);
            $tabMenuElement = $menu->getMenuElements()->toArray();
        }
        else {
            $tabMenuElement = $this->request->getSession()->get(self::SESSION_KEY_ELEMENT_MENU);
        }

        function cmp($a, $b): int
        {
            /*if ($a->getparent() == $b->getparent() && $a->getPosition() == $b->getPosition()) {
                return 0;
            }
            return ($a->getparent() < $b->getparent() && $a->getPosition() < $b->getPosition()) ? -1 : 1;*/

            if ($a->getPosition() == $b->getPosition()) {
                return 0;
            }
            return ($a->getPosition() < $b->getPosition()) ? -1 : 1;
        }
        uasort($tabMenuElement, 'App\Service\Module\Menu\cmp');

        return $tabMenuElement;
    }
}