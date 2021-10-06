<?php
/**
 * Controller global
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Controller
 */
namespace App\Controller;

use App\Service\Admin\System\DataSystemService;
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

    public function __construct(TranslatorInterface $translator, RequestStack $request, OptionService $optionService,
        TranslationService $translationService, DataSystemService $dataSystemService, KernelInterface $kernel)
    {
        $this->translator = $translator;
        $this->request = $request;
        $this->optionService = $optionService;
        $this->session = $this->request->getSession();
        $this->translationService = $translationService;
        $this->dataSystemService = $dataSystemService;
        $this->kernel = $kernel;
    }

}