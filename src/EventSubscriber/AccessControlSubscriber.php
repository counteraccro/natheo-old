<?php

namespace App\EventSubscriber;

use App\Controller\TokenAuthenticatedController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class AccessControlSubscriber implements EventSubscriberInterface
{
    public function __construct()
    {

    }

    public function onKernelController(ControllerEvent $event)
    {
        // TODO control des accès à faire ici via un service
        // throw new AccessDeniedHttpException('This action needs a valid token!');
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}