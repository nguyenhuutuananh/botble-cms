<?php

namespace Botble\Blog\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface TagInterface extends RepositoryInterface
{

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getDataSiteMap();

    /**
     * @param int $limit
     * @return mixed
     * @author Sang Nguyen
     */
    public function getPopularTags($limit);

    /**
     * @param bool $active
     * @return mixed
     * @author Sang Nguyen
     */
    public function getAllTags($active = true);
}
