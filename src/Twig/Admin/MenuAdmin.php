<?php
/**
 * Génération du menu de gauche
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Twig\Admin
 */

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
class MenuAdmin extends AppExtension implements RuntimeExtensionInterface
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
    public function leftMenuAdmin(string $currentPath): string
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

        $html_id = "menu-" . mt_rand();

        return '<li>
                    <a href="#' . $html_id . '" data-bs-toggle="collapse" class="nav-link link-light align-middle dropdown-toggle">
                        <i class="fas ' . $element[self::KEY_ICON] . '"></i> <span class="ms-1 d-none d-sm-inline">' . $this->translator->trans($label) . '</span> 
                    </a>
                        <ul class="collapse ' . $show . ' nav flex-column ms-0" id="' . $html_id . '" data-bs-parent="#menu">' . $html . '
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

    /**
     * Point d'entrée pour le menu top de l'admin
     */
    public function topMenuAdmin()
    {
        $currentLocal = $this->requestStack->getCurrentRequest()->getLocale();
        $listeLangues = $this->translationService->getLocales();
        $user = $this->security->getUser();

        $html = '<ul class="nav justify-content-end">';

        $html .= '<li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                        <i class="fa fa-globe-europe"></i> ' . $this->translator->trans('admin_translation#' . $currentLocal) . '
                    </a>
                    <ul class="dropdown-menu">';

                        foreach($listeLangues as $langue)
                        {
                            $html .= '<li><a class="dropdown-item" href="' . $this->urlGenerator->generate('admin_dashboard_index', ['_locale' => $langue]) . '"><i class="fa fa-flag"></i> ' . $this->translator->trans('admin_translation#' . $langue) . '</a></li>';
                        }
                    $html .= '</ul>
                </li>';

        $html .= '<li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                        <i class="fa fa-user-circle"></i> ' . $user->getEmail() . '
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#"><i class="fa fa-user"></i> ' . $this->translator->trans('admin_topmenu#Mon compte') . '</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fa fa-bell"></i> ' . $this->translator->trans('admin_topmenu#Mes notifications') . '</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="' . $this->urlGenerator->generate('front_security_app_logout') . '"><i class="fa fa-sign-out-alt"></i> ' . $this->translator->trans('front_auth#Déconnexion') . '</a></li>
                    </ul>
                </li>';

        $html .= '</ul>';

        return $html;
    }
}