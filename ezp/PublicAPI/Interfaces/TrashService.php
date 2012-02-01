<?php
/**
 * @package ezp\PublicAPI\Interfaces
 */
namespace ezp\PublicAPI\Interfaces;

use ezp\PublicAPI\Values\Content\Location;
use ezp\PublicAPI\Values\Content\SearchResult;
use ezp\PublicAPI\Values\Content\TrashItem;

/**
 * Location service, used for complex subtree operations
 *
 * @package ezp\PublicAPI\Interfaces
 */
interface TrashService
{
    /**
     * Loads a trashed location object from its $id.
     * 
     * Note that $id is identical to original location, which has been previously trashed
     *
     * @throws \ezp\PublicAPI\Exceptions\UnauthorizedException if the user is not allowd to read the trashed location
     * @throws \ezp\PublicAPI\Exceptions\NotFoundException - if the location with the given id does not exist
     *
     * @param integer $trashItemId
     *
     * @return \ezp\PublicAPI\Values\Content\TrashItem
     */
    public function loadTrashItem( $trashItemId );

    /**
     * Sends $location and all its children to trash and returns the corresponding trash item.
     * 
     * Content is left untouched.
     *
     * @throws \ezp\PublicAPI\Exceptions\UnauthorizedException if the user is not allowd to trash the given location
     *
     * @param \ezp\PublicAPI\Values\Content\Location $location
     *
     * @return \ezp\PublicAPI\Values\Content\TrashItem
     */
    public function trash( Location $location );

    /**
     * Recovers the $trashedLocation at its original place if possible.
     *
     * @throws \ezp\PublicAPI\Exceptions\UnauthorizedException if the user is not allowd to recover the trash item at the parent location location
     * 
     * If $newParentLocation is provided, $trashedLocation will be restored under it.
     *
     * @param \ezp\PublicAPI\Values\Content\TrashItem $trashItem
     * @param \ezp\PublicAPI\Values\Content\LocationCreate $newParentLocation
     *
     * @return \ezp\PublicAPI\Values\Content\Location the newly created or recovered location
     */
    public function recover( TrashItem $trashItem, LocationCreate $newParentLocation = null );

    /**
     * Empties trash.
     * 
     * All locations contained in the trash will be removed. Content objects will be removed
     * if all locations of the content are gone.
     *
     * @throws \ezp\PublicAPI\Exceptions\UnauthorizedException if the user is not allowd to empty the trash
     */
    public function emptyTrash();

    /**
     * Deletes a trash item.
     * 
     * The corresponding content object will be removed
     *
     * @throws \ezp\PublicAPI\Exceptions\UnauthorizedException if the user is not allowd to delete this trash item
     *
     * @param \ezp\PublicAPI\Values\Content\TrashItem $trashItem
     */
    public function deleteTrashItem( TrashItem $trashItem );

    /**
     * Returns a collection of Trashed locations contained in the trash.
     * 
     * $query allows to filter/sort the elements to be contained in the collection.
     *
     * @param \ezp\PublicAPI\Values\Content\Query $query
     *
     * @return \ezp\PublicAPI\Values\Content\SearchResult
     */
    public function findTrashItems( Query $query );
}
