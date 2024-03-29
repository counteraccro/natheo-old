<?php
/**
 * Controller global pour l'admin
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Controller
 */

namespace App\Controller\Admin;

use App\Entity\Admin\Option;
use App\Service\Admin\MediaService;
use App\Service\Admin\PageService;
use App\Service\Admin\System\DataSystemService;
use App\Service\Admin\System\FileService;
use App\Service\Admin\System\OptionService;
use App\Service\Admin\System\SystemService;
use App\Service\Admin\System\TranslationService;
use App\Service\Admin\ThemeService;
use App\Service\Module\FAQ\FaqService;
use App\Service\Module\Menu\MenuService;
use App\Service\Module\TagService;
use Doctrine\Persistence\ManagerRegistry as Doctrine;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\ORM\EntityManagerInterface;

class AppAdminController extends AbstractController
{

    /**
     * @var TranslatorInterface
     */
    protected TranslatorInterface $translator;

    /**
     * @var RequestStack
     */
    protected RequestStack $request;

    /**
     * @var OptionService
     */
    protected OptionService $optionService;

    /**
     * @var SessionInterface
     */
    protected SessionInterface $session;

    /**
     * @var TranslationService
     */
    protected TranslationService $translationService;

    /**
     * @var DataSystemService
     */
    protected DataSystemService $dataSystemService;

    /**
     * @var KernelInterface
     */
    protected KernelInterface $kernel;

    /**
     * @var FileService
     */
    protected FileService $fileService;

    /**
     * @var MediaService
     */
    protected MediaService $mediaService;

    /**
     * @var ThemeService
     */
    protected ThemeService $themeService;

    /**
     * @var Security
     */
    protected Security $security;

    /**
     * @var FaqService
     */
    protected FaqService $faqService;

    /**
     * @var Doctrine
     */
    protected Doctrine  $doctrine;

    /**
     * @var TagService
     */
    protected TagService $tagService;

    /**
     * @var PageService
     */
    protected PageService $pageService;

    /**
     * @var SystemService
     */
    protected SystemService $systemService;

    /**
     * @var MenuService
     */
    protected MenuService $menuService;

    /**
     * @param TranslatorInterface $translator
     * @param RequestStack $request
     * @param OptionService $optionService
     * @param TranslationService $translationService
     * @param DataSystemService $dataSystemService
     * @param KernelInterface $kernel
     * @param FileService $fileService
     * @param MediaService $mediaService
     * @param ThemeService $themeService
     * @param Security $security
     * @param FaqService $faqService
     * @param Doctrine $doctrine
     * @param TagService $tagService
     * @param PageService $pageService
     * @param SystemService $systemService
     */
    public function __construct(TranslatorInterface $translator, RequestStack $request, OptionService $optionService,
                                TranslationService  $translationService, DataSystemService $dataSystemService, KernelInterface $kernel,
                                FileService         $fileService, MediaService $mediaService, ThemeService $themeService, Security $security,
                                FaqService $faqService, Doctrine $doctrine, TagService $tagService, PageService $pageService, SystemService $systemService,
                                MenuService $menuService)
    {
        $this->translator = $translator;
        $this->request = $request;
        $this->optionService = $optionService;
        $this->session = $this->request->getSession();
        $this->translationService = $translationService;
        $this->dataSystemService = $dataSystemService;
        $this->kernel = $kernel;
        $this->fileService = $fileService;
        $this->mediaService = $mediaService;
        $this->themeService = $themeService;
        $this->security = $security;
        $this->faqService = $faqService;
        $this->doctrine = $doctrine;
        $this->tagService = $tagService;
        $this->pageService = $pageService;
        $this->systemService = $systemService;
        $this->menuService = $menuService;
    }

    /**
     * Permet de récupérer les données à filtrée depuis la recherche générique
     * @param string $sessionKey
     * @return mixed
     */
    protected function getCriteriaGeneriqueSearch(string $sessionKey): mixed
    {
        $filter = $this->request->getCurrentRequest()->get('search_data', []);
        if ($filter != null) {

            if ($filter['field'] == 'reset') {
                $filter = null;
            }

            $this->session->set($sessionKey, $filter);
        } else {
            $filter = $this->session->get($sessionKey);
        }
        return $filter;
    }

    /**
     * Met en session la page courant en fonction d'une clé
     * @param string $key
     * @param int $page
     */
    protected function setPageInSession(string $key, int $page)
    {
        $this->session->set($key, $page);
    }

    /**
     * Retourne la page courant en session en fonction d'une clé
     * @param string $key
     * @return mixed
     */
    protected function getPageInSession(string $key): mixed
    {
        return $this->session->get($key, 1);
    }

}