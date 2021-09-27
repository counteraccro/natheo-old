<?php

namespace App\Twig;

use App\Twig\Admin\LeftMenuAdmin;
use App\Twig\Admin\Option;
use App\Twig\Admin\System\Asset;
use App\Twig\Admin\System\Breadcrumb;
use App\Twig\Admin\System\Paginate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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
     * @param ParameterBagInterface $parameterBag
     * @param UrlGeneratorInterface $urlGenerator
     * @param TranslatorInterface $translator
     */
    public function __construct(ParameterBagInterface $parameterBag,
                                UrlGeneratorInterface $urlGenerator,
                                TranslatorInterface $translator)
    {
        $this->parameterBag = $parameterBag;
        $this->urlGenerator = $urlGenerator;
        $this->translator = $translator;
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return [
            // the logic of this filter is now implemented in a different class
            new TwigFilter('leftMenuAdmin', [LeftMenuAdmin::class, 'htmlRender']),
            new TwigFilter('breadcrumb', [Breadcrumb::class, 'htmlRender']),
            new TwigFilter('assetRender', [Asset::class, 'assetAdmin']),
            new TwigFilter('paginate', [Paginate::class, 'htmlRender']),
            new TwigFilter('optionRender', [Option::class, 'htmlRender'])
        ];
    }
}