<?php

namespace App\Controller\Front;

use App\Service\Admin\ThemeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AppFrontController extends AbstractController
{
    /**
     * @var ThemeService
     */
    protected ThemeService $themeService;

    /**
     * @var RequestStack
     */
    protected RequestStack $request;

    /**
     * @var SessionInterface
     */
    protected SessionInterface $session;

   public function __construct(ThemeService $themeService, RequestStack $request)
   {
        $this->themeService = $themeService;
        $this->request = $request;
        $this->session = $request->getSession();
   }
}