<?php

namespace App\EventSubscriber;

use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class qui va injecter de façon automatique la locale courante
 */
class LocaleRewriteSubscriber implements EventSubscriberInterface
{

    /**
     * @var $routeCollection RouteCollection
     */
    private RouteCollection $routeCollection;


    /**
     * @var array
     */
    private array $supportedLocales;


    /**
     * Constructeur
     * @param RouterInterface $router
     * @param array|string[] $supportedLocales
     */
    public function __construct(RouterInterface $router, array $supportedLocales = array('fr'))
    {
        $this->routeCollection = $router->getRouteCollection();
        $this->supportedLocales = $supportedLocales;
    }

    /**
     * @param $locale
     * @return bool
     */
    public function isLocaleSupported($locale): bool
    {
        return in_array($locale, $this->supportedLocales);
    }

    /**
     * @param RequestEvent $event
     */
    public function onKernelRequest(RequestEvent $event)
    {

        $request = $event->getRequest();
        $path = $request->getPathInfo();


        $route_exists = false;

        foreach ($this->routeCollection as $routeObject) {
            $routePath = $routeObject->getPath();

            if ($routePath == "/{_locale}" . $path) {
                $route_exists = true;
                break;
            }
        }


        if ($route_exists == true) {
            $locale = $request->getPreferredLanguage();
            if ($locale == "" || $this->isLocaleSupported($locale) == false) {
                $locale = $request->getDefaultLocale();
            }
            $event->setResponse(new RedirectResponse("/" . $locale . $path));
        }
    }

    /**
     * @return \array[][]
     */
    #[ArrayShape([KernelEvents::REQUEST => "array[]"])]
    public static function getSubscribedEvents(): array
    {
        return array(
            KernelEvents::REQUEST => array(array('onKernelRequest', 17)),
        );
    }
}