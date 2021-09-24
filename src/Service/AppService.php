<?php
/**
 * Service générique
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Service
 */
namespace App\Service;
use Doctrine\Persistence\ManagerRegistry as Doctrine;
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
     * @param Doctrine $doctrine
     * @param RouterInterface $router
     */
    public function __construct(Doctrine $doctrine, RouterInterface $router)
    {
        $this->doctrine = $doctrine;
        $this->router = $router;
    }
}