<?php

namespace App\Twig\Front\System;

use App\Service\Admin\ThemeService;
use App\Twig\Front\AppExtension;
use Twig\Extension\RuntimeExtensionInterface;

class AssetTwig extends AppExtension implements RuntimeExtensionInterface
{
    /**
     * Génère les URLs des assets css sur le front
     * @param string $asset url de l'asset
     */
    public function generateUrlCSSAsset(string $asset)
    {
        $current_theme = $this->themeService->getCurrentTheme();
        return $this->parameterBag->get('app_path_css_theme_asset') . DIRECTORY_SEPARATOR . $current_theme . DIRECTORY_SEPARATOR . $asset;
    }

    /**
     * Génère les URLs des assets js sur le front
     * @param string $asset url de l'asset
     */
    public function generateUrlJSAsset(string $asset)
    {
        $current_theme = $this->themeService->getCurrentTheme();
        return $this->parameterBag->get('app_path_js_theme_asset') . DIRECTORY_SEPARATOR . $current_theme . DIRECTORY_SEPARATOR . $asset;
    }

    /**
     * Génère le path pour les includes twigs pour les templates
     * @param string $view
     * @return string
     */
    public function generatePathIncludeTwig(string $view): string
    {
        $current_theme = $this->themeService->getCurrentTheme();
        return $this->parameterBag->get('app_path_template_theme_include') . DIRECTORY_SEPARATOR . $current_theme . DIRECTORY_SEPARATOR . $view;
    }
}