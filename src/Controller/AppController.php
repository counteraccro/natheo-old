<?php
/**
 * Controller global
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Controller
 */
namespace App\Controller;

use App\Service\Admin\System\OptionService;
use App\Service\Admin\System\TranslationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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

    public function __construct(TranslatorInterface $translator, RequestStack $request, OptionService $optionService, SessionInterface $session,
        TranslationService $translationService)
    {
        $this->translator = $translator;
        $this->request = $request;
        $this->optionService = $optionService;
        $this->session = $session;
        $this->translationService = $translationService;
    }

}