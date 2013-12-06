<?php
/**
 * File containing the RestValueResponseListener class.
 *
 * @copyright Copyright (C) 2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */
namespace eZ\Bundle\EzPublishRestBundle\Tests\EventListener;

use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use eZ\Bundle\EzPublishRestBundle\EventListener\OptionsListener;
use Symfony\Component\Routing\RouterInterface;

class OptionsListenerTest extends EventListenerTest
{
    protected $requestMethod = 'OPTIONS';

    /** @var PHPUnit_Framework_MockObject_MockObject|RouterInterface */
    protected $routerMock;

    public function testNotRestRequest()
    {
        $this->isRestRequest = false;

        $this->requestMethod = false;

        $this->getEventListener()->onKernelRequest(
            $this->getResponseEventMock()
        );
    }

    /**
     * @dataProvider getRequestMethods
     */
    public function testNotOptionsRequest( $requestMethod )
    {
        $this->requestMethod = $requestMethod;

        $this->getEventListener()->onKernelRequest(
            $this->getResponseEventMock()
        );
    }

    public function getRequestMethods()
    {
        return array(
            array( 'GET' ),
            array( 'HEAD' ),
            array( 'POST' ),
            array( 'PUT' ),
            array( 'DELETE' ),
            array( 'PATCH' ),
            array( 'PUBLISH' )
        );
    }

    /**
     * @param bool $csrfEnabled
     *
     * @return \eZ\Bundle\EzPublishRestBundle\EventListener\CsrfListener
     */
    protected function getEventListener()
    {
        return new OptionsListener(
            $this->getRouterMock()
        );
    }

    /**
     * @return PHPUnit_Framework_MockObject_MockObject|RouterInterface
     */
    protected function getRouterMock()
    {
        if ( !isset( $this->routerMock ) )
        {
            $this->routerMock = $this->getMock( 'Symfony\Component\Routing\RouterInterface' );
        }
        return $this->routerMock;
    }

    /**
     * @return PHPUnit_Framework_MockObject_MockObject|GetResponseEvent
     */
    protected function getResponseEventMock()
    {
        if ( !isset( $this->eventMock ) )
        {
            $this->eventMock = parent::getEventMock( 'Symfony\Component\HttpKernel\Event\GetResponseEvent' );
        }
        return $this->eventMock;
    }
}
