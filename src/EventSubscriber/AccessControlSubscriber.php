<?php

namespace App\EventSubscriber;

use App\Controller\TokenAuthenticatedController;
use App\Service\Admin\System\AccessService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class AccessControlSubscriber implements EventSubscriberInterface
{
    /**
     * @var AccessService
     */
    private AccessService $accessService;

    public function __construct(AccessService $accessService)
    {
        $this->accessService = $accessService;
    }

    public function onKernelController(ControllerEvent $event)
    {
        // TODO control des accès à faire ici via un service
        $this->accessService->isGranted($event->getRequest()->attributes->get('_route'));

        // throw new AccessDeniedHttpException('This action needs a valid token!');
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}