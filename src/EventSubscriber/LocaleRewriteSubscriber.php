<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

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


    public function __construct(RouterInterface $router, array $supportedLocales = array('fr'))
    {
        $this->routeCollection = $router->getRouteCollection();
        $this->supportedLocales = $supportedLocales;
    }

    public function isLocaleSupported($locale)
    {
        return in_array($locale, $this->supportedLocales);
    }

    public function onKernelRequest(RequestEvent $event)
    {
        //GOAL:
        // Redirect all incoming requests to their /locale/route equivlent as long as the route will exists when we do so.
        // Do nothing if it already has /locale/ in the route to prevent redirect loops
        $request = $event->getRequest();
        $path = $request->getPathInfo();


        $route_exists = false; //by default assume route does not exist.

        foreach($this->routeCollection as $routeObject){
            $routePath = $routeObject->getPath();

            if($routePath == "/{_locale}".$path){
                $route_exists = true;
                break;
            }
        }

        //If the route does indeed exist then lets redirect there.
        if($route_exists == true){
            //Get the locale from the users browser.
            $locale = $request->getPreferredLanguage();

            //If no locale from browser or locale not in list of known locales supported then set to defaultLocale set in config.yml
            if($locale=="" || $this->isLocaleSupported($locale)==false){
                $locale = $request->getDefaultLocale();
            }

            $event->setResponse(new RedirectResponse("/".$locale.$path));
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            // must be registered before the default Locale listener
            KernelEvents::REQUEST => array(array('onKernelRequest', 17)),
        );
    }
}