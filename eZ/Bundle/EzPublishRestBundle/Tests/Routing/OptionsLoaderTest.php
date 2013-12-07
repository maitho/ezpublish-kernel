<?php
/**
 * File containing the OptionsLoaderTest class.
 *
 * @copyright Copyright (C) 2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */
namespace eZ\Bundle\EzPublishRestBundle\Tests\Routing;

use eZ\Bundle\EzPublishRestBundle\Routing\OptionsLoader;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * @covers \eZ\Bundle\EzPublishRestBundle\Routing\OptionsLoader
 */
class OptionsLoaderTest extends PHPUnit_Framework_TestCase
{
    /**
     * Value returned by the import method of the OptionsLoader mock
     * @var RouteCollection
     */
    protected $importReturnValue;

    public function setUp()
    {
        $this->importReturnValue = new RouteCollection();
    }

    /**
     * @param string $type
     * @param bool $expected
     * @dataProvider getResourceType
     */
    public function testSupportsResourceType( $type, $expected )
    {
        self::assertEquals(
            $expected,
            $this->getOptionsLoader()->supports( null, $type )
        );
    }

    public function getResourceType()
    {
        return array(
            array( 'rest_options', true ),
            array( 'something else', false )
        );
    }

    public function testLoadEmptyCollection()
    {
        $processedCollection = $this->getOptionsLoader()->load( 'EmptyResource', 'rest_options' );
        self::assertEquals( 0, $processedCollection->count() );
    }

    public function testLoad()
    {
        $this->importReturnValue->add(
            'route1',
            new Route(
                '/route/one/{id}',
                array( '_controller' => 'route_one_get' ),
                array( 'id' => '[0-9]+' ),
                array(),
                '',
                array(),
                array( 'GET' )
            )
        );

        $this->importReturnValue->add(
            'route1bis',
            new Route(
                '/route/one/{id}',
                array( '_controller' => 'route_two_post' ),
                array( 'id' => '[0-9]+' ),
                array(),
                '',
                array(),
                array( 'POST' )
            )
        );

        /** @var $processedCollection RouteCollection */
        $processedCollection = $this->getOptionsLoader()->load( 'EmptyResource', 'rest_options' );

        self::assertEquals( 1, $processedCollection->count() );
        self::assertNotNull( $processedRoute = $processedCollection->get( 'ezpublish_rest_options_route_one_{id}' ) );
        self::assertEquals( '/route/one/{id}', $processedRoute->getPath() );
        self::assertEquals( array( 'OPTIONS' ), $processedRoute->getMethods() );
        self::assertEquals(
            array(
                '_controller' => '_ezpublish_rest.controller.options:getRouteOptions',
                '_methods' => 'GET,POST'
            ),
            $processedRoute->getDefaults()
        );

        $expectedRequirements = $this->importReturnValue->get( 'route1' )->getRequirements();
        $processedRequirements = $processedRoute->getRequirements();
        unset( $expectedRequirements['_method'], $processedRequirements['_method'] );

        self::assertEquals( $expectedRequirements, $processedRequirements );
    }

    /**
     * Returns a partially mocked OptionsLoader, with the import method mocked
     * @return OptionsLoader|PHPUnit_Framework_MockObject_MockObject
     */
    protected function getOptionsLoader()
    {
        $mock = $this->getMockBuilder( 'eZ\Bundle\EzPublishRestBundle\Routing\OptionsLoader' )
            ->setMethods( array( 'import' ) )
            ->getMock();

        $mock->expects( $this->any() )
            ->method( 'import' )
            ->with( $this->anything(), $this->anything() )
            ->will( $this->returnValue( $this->importReturnValue ) );

        return $mock;
    }
}
