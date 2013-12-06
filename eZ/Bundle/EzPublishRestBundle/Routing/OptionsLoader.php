<?php
/**
 * File containing the Loader class.
 *
 * @copyright Copyright (C) 2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */
namespace eZ\Bundle\EzPublishRestBundle\Routing;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

/**
 * Goes through all REST routes, and registers new routes for all routes
 * a new one with the OPTIONS method
 */
class OptionsLoader extends Loader
{
    public function load( $resource, $type = null )
    {
        $collection = new RouteCollection();

        /** @var RouteCollection $importedRoute */
        $importedRoutes = $this->import(
            '@EzPublishRestBundle/Resources/config/routing.yml',
            'yaml'
        );
        /** @var Route $route */
        foreach ( $importedRoutes->all() as $route )
        {
            $optionsRouteName = $this->getOptionsRouteName( $route );
            $optionsRoute = $collection->get( $optionsRouteName );

            if ( $optionsRoute === null )
            {
                $optionsRoute = clone( $route );
                $optionsRoute->setMethods( array( 'OPTIONS' ) );
                $optionsRoute->setDefault(
                    '_controller',
                    '_ezpublish_rest.controller.options:getRouteOptions'
                );

                $optionsRoute->setDefault(
                    '_methods',
                    implode( ',', $route->getMethods() )
                );
            }
            else
            {
                $optionsRoute->setDefault(
                    '_methods',
                    $optionsRoute->getDefault( '_methods' ) . ',' . implode( ',', $route->getMethods() )
                );
            }

            $collection->add( $optionsRouteName, $optionsRoute );
        }

        return $collection;
    }

    public function supports( $resource, $type = null )
    {
        return $type === 'rest_options';
    }

    /**
     * Returns the OPTIONS name of a REST route
     * @param $route Route
     * @return string
     */
    protected function getOptionsRouteName( Route $route )
    {
        $name = str_replace( '/', '_', $route->getPath() );
        return 'ezpublish_rest_options_' . trim( $name, '_' );
    }
}
