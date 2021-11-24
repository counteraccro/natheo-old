<?php

namespace App\Twig\Front;

use App\Service\Admin\System\AccessService;
use App\Service\Admin\System\DateService;
use App\Service\Admin\System\OptionService;
use App\Service\Admin\System\TranslationService;
use App\Service\Admin\ThemeService;
use App\Twig\Front\System\AssetTwig;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    /**
     * @var ParameterBagInterface
     */
    protected ParameterBagInterface $parameterBag;

    /**
     * @var UrlGeneratorInterface
     */
    protected UrlGeneratorInterface $urlGenerator;

    /**
     * @var TranslatorInterface
     */
    protected TranslatorInterface $translator;

    /**
     * @var RequestStack
     */
    protected  RequestStack $requestStack;

    /**
     * @var TranslationService
     */
    protected TranslationService $translationService;

    /**
     * @var Security
     */
    protected Security $security;

    /**
     * @var SessionInterface
     */
    protected SessionInterface $session;

    /**
     * @var OptionService
     */
    protected OptionService $optionService;

    /**
     * @var DateService
     */
    protected DateService $dateService;

    /**
     * @var KernelInterface
     */
    protected KernelInterface $kernel;

    /**
     * @var ThemeService
     */
    protected ThemeService $themeService;


    /**
     * @var AccessService
     */
    protected AccessService $accessService;


    /**
     * @param ParameterBagInterface $parameterBag
     * @param UrlGeneratorInterface $urlGenerator
     * @param TranslatorInterface $translator
     * @param Security $security
     * @param RequestStack $requestStack
     * @param TranslationService $translationService
     * @param OptionService $optionService
     * @param DateService $dateService
     * @param KernelInterface $kernel
     * @param AccessService $accessService
     */
    public function __construct(ParameterBagInterface $parameterBag, UrlGeneratorInterface $urlGenerator,
                                TranslatorInterface   $translator, Security $security,
                                RequestStack          $requestStack, TranslationService $translationService,
                                OptionService       $optionService, DateService $dateService, KernelInterface $kernel,
                                AccessService $accessService, ThemeService $themeService)
    {
        $this->parameterBag = $parameterBag;
        $this->urlGenerator = $urlGenerator;
        $this->translator = $translator;
        $this->requestStack = $requestStack;
        $this->translationService = $translationService;
        $this->security = $security;
        $this->session = $this->requestStack->getSession();
        $this->optionService = $optionService;
        $this->dateService = $dateService;
        $this->kernel = $kernel;
        $this->accessService = $accessService;
        $this->themeService = $themeService;
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return [
            // the logic of this filter is now implemented in a different class
            new TwigFilter('cms_asset_css_url', [AssetTwig::class, 'generateUrlCSSAsset']),
            new TwigFilter('cms_asset_js_url', [AssetTwig::class, 'generateUrlJSAsset']),
            new TwigFilter('cms_path_template', [AssetTwig::class, 'generatePathIncludeTwig'])
        ];
    }
}