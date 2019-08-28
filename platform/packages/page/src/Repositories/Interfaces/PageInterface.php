<?php

namespace Botble\Page\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface PageInterface extends RepositoryInterface
{

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getDataSiteMap();

    /**
     * @param $limit
     * @author Sang Nguyen
     */
    public function getFeaturedPages($limit);

    /**
     * @param $array
     * @param array $select
     * @return mixed
     * @author Sang Nguyen
     */
    public function whereIn($array, $select = []);

    /**
     * @param $query
     * @param int $limit
     * @return mixed
     * @author Sang Nguyen
     */
    public function getSearch($query, $limit = 10);

    /**
     * @param bool $active
     * @return mixed
     * @author Sang Nguyen
     */
    public function getAllPages($active = true);
}
