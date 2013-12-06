<?php
/**
 * File containing the RestValueResponseListener class.
 *
 * @copyright Copyright (C) 2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */
namespace eZ\Bundle\EzPublishRestBundle\EventListener;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

/**
 * Listens for OPTIONS REST requests, and delegates generation of the Response
 */
class OptionsListener implements EventSubscriberInterface
{
    /**
     * @param $router RouterInterface
     */
    public function __construct( RouterInterface $router )
    {
        $this->router = $router;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => 'onKernelRequest',
        );
    }

    /**
     * This method validates CSRF token if CSRF protection is enabled.
     *
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     *
     * @throws \eZ\Publish\Core\Base\Exceptions\UnauthorizedException
     */
    public function onKernelRequest( GetResponseEvent $event )
    {
        if ( !$event->getRequest()->attributes->get( 'is_rest_request' ) )
            return;

        if ( $event->getRequest()->getMethod() != 'OPTIONS' )
        {
            return;
        }

        // get the referenced verbs for this route
        $pathinfo = $event->getRequest()->getPathInfo();
        $try = $this->router->match(
            $pathinfo
        );
    }
}
