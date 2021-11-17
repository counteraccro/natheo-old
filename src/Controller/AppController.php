<?php
/**
 * Controller global
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Controller
 */

namespace App\Controller;

use App\Entity\Admin\Option;
use App\Service\Admin\MediaService;
use App\Service\Admin\System\DataSystemService;
use App\Service\Admin\System\FileService;
use App\Service\Admin\System\OptionService;
use App\Service\Admin\System\TranslationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class AppController extends AbstractController
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
     * @param TranslatorInterface $translator
     * @param RequestStack $request
     * @param OptionService $optionService
     * @param TranslationService $translationService
     * @param DataSystemService $dataSystemService
     * @param KernelInterface $kernel
     * @param FileService $fileService
     */
    public function __construct(TranslatorInterface $translator, RequestStack $request, OptionService $optionService,
                                TranslationService  $translationService, DataSystemService $dataSystemService, KernelInterface $kernel,
                                FileService         $fileService, MediaService $mediaService)
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