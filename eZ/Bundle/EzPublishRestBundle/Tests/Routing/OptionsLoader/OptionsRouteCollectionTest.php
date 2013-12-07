<?php
/**
 * File containing the OptionsRouteCollectionTest class.
 *
 * @copyright Copyright (C) 2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */
namespace eZ\Bundle\EzPublishRestBundle\Tests\Routing\OptionsLoader;

use eZ\Bundle\EzPublishRestBundle\Routing\OptionsLoader\OptionsRouteCollection;
use eZ\Bundle\EzPublishRestBundle\Routing\OptionsLoader\Mapper;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * @covers eZ\Bundle\EzPublishRestBundle\Routing\OptionsLoader\OptionsRouteCollection
 */
class OptionsRouteCollectionTest extends PHPUnit_Framework_TestCase
{
    /** @var OptionsRouteCollection */
    protected $optionsRouteCollection;

    public function setUp()
    {
        $this->optionsRouteCollection = new OptionsRouteCollection(
            new Mapper()
        );
    }

    public function testAddRestRoutesCollection()
    {
        $restRoutesCollection = new RouteCollection();
        $restRoutesCollection->add( 'ezpublish_rest_route_one_get', $this->createRoute( '/route/one', array( 'GET' ) ) );
        $restRoutesCollection->add( 'ezpublish_rest_route_one_post', $this->createRoute( '/route/one', array( 'POST' ) ) );
        $restRoutesCollection->add( 'ezpublish_rest_route_two_delete', $this->createRoute( '/route/two', array( 'DELETE' ) ) );

        $this->optionsRouteCollection->addRestRoutesCollection( $restRoutesCollection );

        self::assertEquals(
            2,
            $this->optionsRouteCollection->count()
        );

        self::assertInstanceOf(
            'Symfony\Component\Routing\Route',
            $this->optionsRouteCollection->get( 'ezpublish_rest_options_route_one' )
        );

        self::assertInstanceOf(
            'Symfony\Component\Routing\Route',
            $this->optionsRouteCollection->get( 'ezpublish_rest_options_route_two' )
        );

        self::assertEquals(
            'GET,POST',
            $this->optionsRouteCollection->get( 'ezpublish_rest_options_route_one' )->getDefault( '_methods' )
        );

        self::assertEquals(
            'DELETE',
            $this->optionsRouteCollection->get( 'ezpublish_rest_options_route_two' )->getDefault( '_methods' )
        );
    }

    /**
     * @param string $path
     * @param array $methods
     * @return Route
     */
    private function createRoute( $path, array $methods )
    {
        return new Route( $path, array(), array(), array(), '', array(), $methods );
    }
}
