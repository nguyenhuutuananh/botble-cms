<?php

namespace Botble\Gallery\Repositories\Eloquent;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Gallery\Repositories\Interfaces\GalleryInterface;

class GalleryRepository extends RepositoriesAbstract implements GalleryInterface
{

    /**
     * {@inheritdoc}
     */
    protected $screen = GALLERY_MODULE_SCREEN_NAME;

    /**
     * {@inheritdoc}
     */
    public function getAll()
    {
        $data = $this->model
            ->where('galleries.status', '=', BaseStatusEnum::PUBLISH)
            ->orderBy('galleries.order', 'asc')
            ->orderBy('galleries.created_at', 'desc');

        return $this->applyBeforeExecuteQuery($data, $this->screen)->get();
    }

    /**
     * {@inheritdoc}
     */
    public function getDataSiteMap()
    {
        $data = $this->model
            ->where('galleries.status', '=', BaseStatusEnum::PUBLISH)
            ->select('galleries.*')
            ->orderBy('galleries.created_at', 'desc');

        return $this->applyBeforeExecuteQuery($data, $this->screen)->get();
    }

    /**
     * {@inheritdoc}
     */
    public function getFeaturedGalleries($limit)
    {
        $data = $this->model
            ->with(['user'])
            ->where([
                'galleries.status'   => BaseStatusEnum::PUBLISH,
                'galleries.is_featured' => 1,
            ])
            ->select([
                'galleries.id',
                'galleries.name',
                'galleries.user_id',
                'galleries.image',
                'galleries.created_at',
            ])
            ->orderBy('galleries.order', 'asc')
            ->limit($limit);

        return $this->applyBeforeExecuteQuery($data, $this->screen)->get();
    }
}
