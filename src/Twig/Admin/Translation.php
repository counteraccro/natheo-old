<?php

namespace App\Twig\Admin;

use App\Service\Admin\System\TranslationService;
use App\Twig\AppExtension;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\RuntimeExtensionInterface;

class Translation extends AppExtension implements RuntimeExtensionInterface
{

    /**
     * @var TranslationService
     */
    private TranslationService $translationService;

    #[Pure] public function __construct(ParameterBagInterface $parameterBag, UrlGeneratorInterface $urlGenerator, TranslatorInterface $translator, TranslationService $translationService)
    {
        parent::__construct($parameterBag, $urlGenerator, $translator);
        $this->translationService = $translationService;
    }

    /**
     * Génère le formulaire de recherche pour les traductions
     */
    public function formSearchTranslation()
    {
        $listeApp = $this->translationService->getApplications();
        var_dump($listeApp);

        $listeModules = $this->translationService->getModules();
        var_dump($listeModules);
    }
}