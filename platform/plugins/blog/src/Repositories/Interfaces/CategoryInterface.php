<?php

namespace Botble\Blog\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Support\Collection;

interface CategoryInterface extends RepositoryInterface
{

    /**
     * @return mixed
     * 
     */
    public function getDataSiteMap();

    /**
     * @param int $limit
     * 
     */
    public function getFeaturedCategories($limit);

    /**
     * @param array $condition
     * @return mixed
     * 
     */
    public function getAllCategories(array $condition = []);

    /**
     * @param int $id
     * @return mixed
     */
    public function getCategoryById($id);

    /**
     * @param array $select
     * @param array $orderBy
     * @return Collection
     */
    public function getCategories(array $select, array $orderBy);

    /**
     * @param int $id
     * @return array|null
     */
    public function getAllRelatedChildrenIds($id);

    /**
     * @param array $condition
     * @param array $with
     * @param array $select
     * @return mixed
     * 
     */
    public function getAllCategoriesWithChildren(array $condition = [], array $with = [], array $select = ['*']);
}
