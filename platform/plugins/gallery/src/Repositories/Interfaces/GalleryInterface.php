<?php

namespace Botble\Gallery\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface GalleryInterface extends RepositoryInterface
{

    /**
     * Get all galleries.
     *
     * @return mixed
     * @author Sang Nguyen
     */
    public function getAll();

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getDataSiteMap();

    /**
     * @param $limit
     * @author Sang Nguyen
     */
    public function getFeaturedGalleries($limit);
}
