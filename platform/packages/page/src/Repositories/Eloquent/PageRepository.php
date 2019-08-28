<?php

namespace Botble\Page\Repositories\Eloquent;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Page\Repositories\Interfaces\PageInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class PageRepository extends RepositoriesAbstract implements PageInterface
{

    /**
     * {@inheritdoc}
     */
    protected $screen = PAGE_MODULE_SCREEN_NAME;

    /**
     * {@inheritdoc}
     */
    public function getDataSiteMap()
    {
        $data = $this->model
            ->where('pages.status', BaseStatusEnum::PUBLISH)
            ->select('pages.*')
            ->orderBy('pages.created_at', 'desc');

        return $this->applyBeforeExecuteQuery($data, $this->screen)->get();
    }

    /**
     * {@inheritdoc}
     */
    public function getFeaturedPages($limit)
    {
        $data = $this->model
            ->where(['pages.status' => BaseStatusEnum::PUBLISH, 'pages.is_featured' => 1])
            ->orderBy('pages.created_at', 'asc')
            ->select('pages.*')
            ->limit($limit)
            ->orderBy('pages.created_at', 'desc');

        return $this->applyBeforeExecuteQuery($data, $this->screen)->get();
    }

    /**
     * {@inheritdoc}
     */
    public function whereIn($array, $select = [])
    {
        $pages = $this->model
            ->whereIn('pages.id', $array)
            ->where('pages.status', BaseStatusEnum::PUBLISH);

        if (empty($select)) {
            $select = 'pages.*';
        }
        $data = $pages
            ->select($select)
            ->orderBy('pages.created_at', 'ASC');

        return $this->applyBeforeExecuteQuery($data, $this->screen)->get();
    }

    /**
     * {@inheritdoc}
     */
    public function getSearch($query, $limit = 10)
    {
        $pages = $this->model->where('pages.status', BaseStatusEnum::PUBLISH);
        foreach (explode(' ', $query) as $term) {
            $pages = $pages->where('pages.name', 'LIKE', '%' . $term . '%');
        }

        $data = $pages
            ->select('pages.*')
            ->orderBy('pages.created_at', 'desc')
            ->limit($limit);

        return $this->applyBeforeExecuteQuery($data, $this->screen)->get();
    }

    /**
     * {@inheritdoc}
     */
    public function getAllPages($active = true)
    {
        $data = $this->model->select('pages.*');
        if ($active) {
            $data = $data->where(['pages.status' => BaseStatusEnum::PUBLISH]);
        }

        return $this->applyBeforeExecuteQuery($data, $this->screen)->get();
    }
}
