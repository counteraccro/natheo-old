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

    public function onKernelController(ControllerEvent $event)
    {
        // TODO control des accès à faire ici via un service
        if(!$this->accessService->isGranted($event->getRequest()->attributes->get('_route')))
        {
            echo $this->twig->render('admin/errors/no-access.html.twig');
            die();

        }
        // throw new AccessDeniedHttpException('This action needs a valid token!');
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}