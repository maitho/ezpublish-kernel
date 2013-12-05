<?php
/**
 * File containing the RestValueResponseListener class.
 *
 * @copyright Copyright (C) 2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */
namespace eZ\Bundle\EzPublishRestBundle\Tests\EventListener;

use eZ\Publish\Core\REST\Server\View\AcceptHeaderVisitorDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use eZ\Bundle\EzPublishRestBundle\EventListener\RequestListener;
use eZ\Publish\Core\Base\Exceptions\UnauthorizedException;
use PHPUnit_Framework_TestCase;

class OptionsListenerTest extends PHPUnit_Framework_TestCase
{
}
