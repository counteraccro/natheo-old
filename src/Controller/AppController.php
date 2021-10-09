<?php
/**
 * Controller global
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Controller
 */
namespace App\Controller;

use App\Entity\Admin\Option;
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

    /**
     * Permet de récupérer l'option GO_ADM_GLOBAL_ELEMENT_PAR_PAGE
     * @param bool $onlyValue True uniquement valeur sinon objet option
     * @return Option|string
     */
    protected function getOptionElementParPage(bool $onlyValue = true): string|Option
    {
        return $this->optionService->getOptionByKey(OptionService::GO_ADM_GLOBAL_ELEMENT_PAR_PAGE, OptionService::GO_ADM_GLOBAL_ELEMENT_PAR_PAGE_DEFAULT_VALUE, true);
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

            if($filter['field'] == 'reset')
            {
                $filter = null;
            }

            $this->session->set($sessionKey, $filter);
        } else {
            $filter = $this->session->get($sessionKey);
        }
        return $filter;
    }

}