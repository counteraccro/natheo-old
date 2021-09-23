<?php

namespace App\Twig\Admin;

use App\Twig\AppExtension;
use phpDocumentor\Reflection\Types\Self_;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Yaml\Yaml;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\RuntimeExtensionInterface;

/**
 * Permet de générer le menu de gauche de l'administration
 */
class LeftMenuAdmin extends AppExtension implements RuntimeExtensionInterface
{

    /**
     * Constantes clés du tableau YAML
     */
    const KEY_ICON = 'icon';
    const KEY_TARGET = 'target';
    const KEY_SUB_MENU = 'sub-menu';

    /**
     * Menu récupéré depuis le fichier de config
     * @var array
     */
    private array $menu;

    /**
     * Point d'entrée pour la génération du menu de gauche de l'administration
     * @param string $currentPath
     * @return string
     */
    public function htmlRender(string $currentPath): string
    {
        $this->getYamlMenu();

        $html = '';
        foreach($this->menu['menu-left-admin'] as $label => $element)
        {
            if(isset($element[self::KEY_SUB_MENU]))
            {
                $html .= $this->generateSubElementMenu($label, $element, $currentPath);
            }
            else {
                $html .= $this->generateElementMenu($label, $element, $currentPath);
            }
        }
        return $html;
    }

    /**
     * Génére un element de menu simple
     * @param String $label
     * @param array $element
     * @param string $currentPath
     * @return string
     */
    private function generateElementMenu(String $label, array $element, string $currentPath): string
    {
        $active = '';
        if($element[self::KEY_TARGET] == $currentPath)
        {
            $active = 'active';
        }

        return '<li class="nav-item">
                        <a href="' . $this->urlGenerator->generate($element[self::KEY_TARGET]) . '" class="nav-link link-light align-middle ' . $active . '">
                            <i class="fas ' . $element[self::KEY_ICON] . '"></i> <span class="ms-1 d-none d-sm-inline">' . $this->translator->trans($label) . '</span>
                        </a>
                    </li>';
    }

    /**
     * Génère un element de menu avec sous menu
     * @param String $label
     * @param array $element
     * @param string $currentPath
     * @return string
     */
    private function generateSubElementMenu(String $label, array $element, string $currentPath): string
    {

        $html = '';
        $show = '';
        foreach($element[self::KEY_SUB_MENU] as $subLabel => $subElement)
        {
            $active = '';
            if($subElement[self::KEY_TARGET] == $currentPath)
            {
                $active = 'active';
                $show = 'show';
            }

                $html .= '<li>
                            <a href="' . $this->urlGenerator->generate($subElement[self::KEY_TARGET]) . '" class="nav-link link-light ' . $active . '"> <i class="fa ' . $subElement[self::KEY_ICON] . '"></i> <span class="d-none d-sm-inline">' . $this->translator->trans($subLabel) . '</span></a>
                          </li>';
        }

        return '<li>
                    <a href="#submenu1" data-bs-toggle="collapse" class="nav-link link-light align-middle dropdown-toggle">
                        <i class="fas ' . $element[self::KEY_ICON] . '"></i> <span class="ms-1 d-none d-sm-inline">' . $this->translator->trans($label) . '</span> 
                    </a>
                        <ul class="collapse ' . $show . ' nav flex-column ms-0" id="submenu1" data-bs-parent="#menu">' . $html . '
                        </ul>
                 </li>';
    }

    /**
     * Récupération du menu depuis la config
     */
    private function getYamlMenu()
    {
        $this->menu = Yaml::parseFile($this->parameterBag->get('app_path_menu_left_admin'));
    }
}