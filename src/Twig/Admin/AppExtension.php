<?php

namespace App\Twig\Admin;

use App\Entity\Media\Media;
use App\Service\Admin\MediaService;
use App\Service\Admin\System\AccessService;
use App\Service\Admin\System\DateService;
use App\Service\Admin\System\FileUploaderService;
use App\Service\Admin\System\OptionService;
use App\Service\Admin\System\TranslationService;
use App\Twig\Admin\Media\MediaTwig;
use App\Twig\Admin\Media\TreeFolderTwig;
use App\Twig\Admin\MenuAdminTwig;
use App\Twig\Admin\OptionTwig;
use App\Twig\Admin\RoleTwig;
use App\Twig\Admin\System\AssetTwig;
use App\Twig\Admin\System\BreadcrumbTwig;
use App\Twig\Admin\System\DateTwig;
use App\Twig\Admin\System\FileTwig;
use App\Twig\Admin\System\PaginateTwig;
use App\Twig\Admin\System\GlobalFunctionTwig;
use App\Twig\Admin\ThemeTwig;
use App\Twig\Admin\TranslationTwig;
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
     * @var FileUploaderService
     */
    protected FileUploaderService $fileUploaderService;

    /**
     * @var AccessService
     */
    protected AccessService $accessService;

    /**
     * @var MediaService
     */
    protected MediaService $mediaService;

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
     * @param FileUploaderService $fileUploaderService
     * @param AccessService $accessService
     * @param MediaService $mediaService
     */
    public function __construct(ParameterBagInterface $parameterBag, UrlGeneratorInterface $urlGenerator,
                                TranslatorInterface   $translator, Security $security,
                                RequestStack          $requestStack, TranslationService $translationService,
                                OptionService       $optionService, DateService $dateService, KernelInterface $kernel,
                                FileUploaderService $fileUploaderService, AccessService $accessService,
                                MediaService $mediaService)
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
        $this->fileUploaderService = $fileUploaderService;
        $this->accessService = $accessService;
        $this->mediaService = $mediaService;
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return [
            // the logic of this filter is now implemented in a different class
            new TwigFilter('leftMenuAdmin', [MenuAdminTwig::class, 'leftMenuAdmin']),
            new TwigFilter('topMenuAdmin', [MenuAdminTwig::class, 'topMenuAdmin']),
            new TwigFilter('breadcrumb', [BreadcrumbTwig::class, 'htmlRender']),
            new TwigFilter('assetRender', [AssetTwig::class, 'assetAdmin']),
            new TwigFilter('paginate', [PaginateTwig::class, 'htmlRender']),
            new TwigFilter('optionRender', [OptionTwig::class, 'htmlRender']),
            new TwigFilter('formSearchTranslation', [TranslationTwig::class, 'formSearchTranslation']),
            new TwigFilter('inputHidden', [GlobalFunctionTwig::class, 'generateHiddenInput']),
            new TwigFilter('inputSearch', [GlobalFunctionTwig::class, 'generateSearchInput']),
            new TwigFilter('scriptBeforLeave', [GlobalFunctionTwig::class, 'checkBeforLeaveJS']),
            new TwigFilter('isGranted', [GlobalFunctionTwig::class, 'isGranted']),
            new TwigFilter('dateFooter', [GlobalFunctionTwig::class, 'dateFooter']),
            new TwigFilter('listeRouteRight', [RoleTwig::class, 'generateRouteRight']),
            new TwigFilter('listeModules', [RoleTwig::class, 'getListeModules']),
            new TwigFilter('pathAvatar', [FileTwig::class, 'getPathAvatar']),
            new TwigFilter('dateFormat', [DateTwig::class, 'dateFormat']),
            new TwigFilter('treeFolder', [TreeFolderTwig::class, 'treeFolder']),
            new TwigFilter('pathFolder', [TreeFolderTwig::class, 'getPathFolder']),
            new TwigFilter('pathFolder', [TreeFolderTwig::class, 'getPathFolder']),
            new TwigFilter('renderContentMedia', [MediaTwig::class, 'renderModeMedia']),
            new TwigFilter('treeThemeFolder', [ThemeTwig::class, 'getTreeByThemeFolder'])

        ];
    }
}