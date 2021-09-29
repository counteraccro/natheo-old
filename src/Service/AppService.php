<?php
/**
 * Service générique
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Service
 */
namespace App\Service;
use App\Service\Admin\System\TranslationService;
use Doctrine\Persistence\ManagerRegistry as Doctrine;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\RouterInterface;

class AppService
{

    /**
     * @var Doctrine
     */
    protected $doctrine;

    /**
     * @var RouterInterface;
     */
    protected RouterInterface $router;

    /**
     * @var Request
     */
    protected mixed $request;

    /**
     * @var ParameterBagInterface
     */
    protected ParameterBagInterface $parameterBag;

    /**
     * @var KernelInterface
     */
    protected KernelInterface $kernel;

    /**
     * @param Doctrine $doctrine
     * @param RouterInterface $router
     * @param RequestStack $request
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(Doctrine $doctrine, RouterInterface $router, RequestStack $request,
            ParameterBagInterface $parameterBag, KernelInterface $kernel)
    {
        $this->doctrine = $doctrine;
        $this->router = $router;
        $this->request = $request->getCurrentRequest();
        $this->parameterBag = $parameterBag;
        $this->kernel = $kernel;
    }
}