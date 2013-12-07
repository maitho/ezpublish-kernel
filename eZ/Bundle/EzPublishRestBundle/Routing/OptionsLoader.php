<?php
/**
 * File containing the Loader class.
 *
 * @copyright Copyright (C) 2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */
namespace eZ\Bundle\EzPublishRestBundle\Routing;

use eZ\Bundle\EzPublishRestBundle\Routing\OptionsLoader\Mapper;
use Symfony\Component\Config\Loader\Loader;
use eZ\Bundle\EzPublishRestBundle\Routing\OptionsLoader\OptionsRouteCollection;
use Symfony\Component\Routing\Route;

/**
 * Goes through all REST routes, and registers new routes for all routes
 * a new one with the OPTIONS method
 */
class OptionsLoader extends Loader
{
    /** @var Mapper */
    protected $mapper;

    public function __construct( Mapper $mapper )
    {
        $this->mapper = $mapper;
    }

    /**
     * @param mixed $resource
     * @param string $type
     *
     * @return OptionsRouteCollection
     */
    public function load( $resource, $type = null )
    {
        $collection = new OptionsRouteCollection( $this->mapper );
        $collection->addRestRoutesCollection( $this->import( $resource ) );

        return $collection;
    }

    public function supports( $resource, $type = null )
    {
        return $type === 'rest_options';
    }
}
