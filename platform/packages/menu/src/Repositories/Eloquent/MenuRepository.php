<?php

namespace Botble\Menu\Repositories\Eloquent;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Menu\Repositories\Interfaces\MenuInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Illuminate\Support\Str;

class MenuRepository extends RepositoriesAbstract implements MenuInterface
{

    /**
     * {@inheritdoc}
     */
    protected $screen = MENU_MODULE_SCREEN_NAME;

    /**
     * {@inheritdoc}
     */
    public function findBySlug($slug, $active, $selects = [])
    {
        $data = $this->model->where('menus.slug', '=', $slug);
        if ($active) {
            $data = $data->where('menus.status', '=', BaseStatusEnum::PUBLISH)->select($selects);
        }
        $data = $this->applyBeforeExecuteQuery($data, $this->screen, true)->first();

        $this->resetModel();

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function createSlug($name)
    {
        $slug = Str::slug($name);
        $index = 1;
        $baseSlug = $slug;
        while ($this->model->where('menus.slug', $slug)->count() > 0) {
            $slug = $baseSlug . '-' . $index++;
        }

        if (empty($slug)) {
            $slug = time();
        }

        $this->resetModel();

        return $slug;
    }
}
