<?php
/**
 * File containing the MapperTest class.
 *
 * @copyright Copyright (C) 2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */
namespace eZ\Bundle\EzPublishRestBundle\Tests\Routing\OptionsLoader;

use eZ\Bundle\EzPublishRestBundle\Routing\OptionsLoader\Mapper;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Routing\Route;

class MapperTest extends PHPUnit_Framework_TestCase
{
    /** @var Mapper */
    protected $mapper;

    public function setUp()
    {
        $this->mapper = new Mapper;
    }

    public function testGetOptionsRouteName()
    {
        $route = new Route( '/route/{id}' );

        self::assertEquals(
            'ezpublish_rest_options_route_{id}',
            $this->mapper->getOptionsRouteName( $route )
        );
    }

    public function testMergeMethodsDefault()
    {
        $restRoute = new Route( '', array( '_methods' => 'PUT,DELETE' ) );
        $optionsRoute = new Route( '', array(), array(), array(), '', array(), array( 'GET,POST' ) );

        self::assertEquals(
            'PUT,DELETE,GET,POST',
            $this->mapper->mergeMethodsDefault( $optionsRoute, $restRoute )->getDefault( '_methods' )
        );
    }

    public function testMapRoute()
    {
        $restRoute = new Route(
            '/route/one/{id}',
            array( '_controller' => 'anything' ),
            array( 'id' => '[0-9]+' ),
            array(),
            '',
            array(),
            array( 'PUT', 'DELETE' )
        );

        $optionsRoute = $this->mapper->mapRoute( $restRoute );

        self::assertEquals(
            array( 'OPTIONS' ),
            $optionsRoute->getMethods()
        );

        self::assertEquals(
            $restRoute->getRequirement( 'id' ),
            $optionsRoute->getRequirement( 'id' )
        );

        self::assertEquals(
            'PUT,DELETE',
            $optionsRoute->getDefault( '_methods' )
        );

        self::assertEquals(
            '_ezpublish_rest.controller.options:getRouteOptions',
            $optionsRoute->getDefault( '_controller' )
        );
    }
}
