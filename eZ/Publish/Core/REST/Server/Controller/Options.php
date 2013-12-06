<?php
/**
 * File containing the Root controller class
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\REST\Server\Controller;

use eZ\Publish\Core\REST\Server\Values;
use eZ\Publish\Core\REST\Server\Controller as RestController;

/**
 * Root controller
 */
class Options extends RestController
{
    /**
     * Lists the verbs available for a resource
     */
    public function getRouteOptions( $_methods )
    {
        return new Values\Options( explode( ',', $_methods ) );
    }

    /**
     * Catch-all for REST requests
     *
     * @throws \eZ\Publish\Core\REST\Common\Exceptions\NotFoundException
     */
    public function catchAll()
    {
        throw new NotFoundException( "No such route" );
    }
}
