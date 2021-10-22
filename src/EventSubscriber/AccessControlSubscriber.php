<?php

namespace App\EventSubscriber;

use App\Controller\TokenAuthenticatedController;
use App\Service\Admin\System\AccessService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AccessControlSubscriber implements EventSubscriberInterface
{
    /**
     * @var AccessService
     */
    private AccessService $accessService;

    /**
     * @var Environment
     */
    private Environment $twig;

    public function __construct(AccessService $accessService, Environment $twig)
    {
        $this->accessService = $accessService;
        $this->twig = $twig;

    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function onKernelController(ControllerEvent $event)
    {
        // SI la personne n'a pas les droits on la redirige vers une page d'erreur
        if(!$this->accessService->isGranted($event->getRequest()->attributes->get('_route')))
        {
            if(str_contains($event->getRequest()->attributes->get('_route'), '_ajax_') === true)
            {
                echo $this->twig->render('admin/errors/no-access-ajax.html.twig');
            }
            else {
                echo $this->twig->render('admin/errors/no-access.html.twig');
            }

            die();
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}