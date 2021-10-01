<?php

namespace App\Twig;

use App\Service\Admin\System\TranslationService;
use App\Twig\Admin\MenuAdmin;
use App\Twig\Admin\Option;
use App\Twig\Admin\System\Asset;
use App\Twig\Admin\System\Breadcrumb;
use App\Twig\Admin\System\Paginate;
use App\Twig\Admin\System\GlobalFunction;
use App\Twig\Admin\Translation;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

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
     * @param ParameterBagInterface $parameterBag
     * @param UrlGeneratorInterface $urlGenerator
     * @param TranslatorInterface $translator
     * @param Security $security
     * @param RequestStack $requestStack
     * @param TranslationService $translationService
     */
    public function __construct(ParameterBagInterface $parameterBag,
                                UrlGeneratorInterface $urlGenerator,
                                TranslatorInterface $translator, Security $security,
                                RequestStack $requestStack, TranslationService $translationService)
    {
        $this->parameterBag = $parameterBag;
        $this->urlGenerator = $urlGenerator;
        $this->translator = $translator;
        $this->requestStack = $requestStack;
        $this->translationService = $translationService;
        $this->security = $security;
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return [
            // the logic of this filter is now implemented in a different class
            new TwigFilter('leftMenuAdmin', [MenuAdmin::class, 'leftMenuAdmin']),
            new TwigFilter('topMenuAdmin', [MenuAdmin::class, 'topMenuAdmin']),
            new TwigFilter('breadcrumb', [Breadcrumb::class, 'htmlRender']),
            new TwigFilter('assetRender', [Asset::class, 'assetAdmin']),
            new TwigFilter('paginate', [Paginate::class, 'htmlRender']),
            new TwigFilter('optionRender', [Option::class, 'htmlRender']),
            new TwigFilter('formSearchTranslation', [Translation::class, 'formSearchTranslation']),
            new TwigFilter('inputHidden', [GlobalFunction::class, 'generateHiddenInput'])
        ];
    }
}