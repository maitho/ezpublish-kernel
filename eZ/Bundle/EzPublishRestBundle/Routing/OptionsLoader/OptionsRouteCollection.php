<?php
/**
 * File containing the OptionsRouteCollection class.
 *
 * @copyright Copyright (C) 2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */
namespace eZ\Bundle\EzPublishRestBundle\Routing\OptionsLoader;

use Symfony\Component\Routing\RouteCollection;

class OptionsRouteCollection extends RouteCollection
{
    /**
     * @var Mapper
     */
    protected $mapper;

    public function __construct( Mapper $mapper )
    {
        $this->mapper = $mapper;
    }

    /**
     * Iterates $restRoutes, and adds unique, merged OPTIONS rest routes to the collection
     * @param RouteCollection $collection
     */
    public function addRestRoutesCollection( RouteCollection $collection )
    {
        foreach ( $collection->all() as $restRoute )
        {
            $optionsRouteName = $this->mapper->getOptionsRouteName( $restRoute );

            $optionsRoute = $collection->get( $optionsRouteName );
            if ( $optionsRoute === null )
            {
                $optionsRoute = $this->mapper->mapRoute( $restRoute );
            }
            else
            {
                $this->mapper->mergeMethodsDefault( $restRoute, $optionsRoute );
            }

            $this->add( $optionsRouteName, $optionsRoute );
        }
    }
}
