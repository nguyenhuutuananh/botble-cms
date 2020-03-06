<?php

namespace Botble\Media\Repositories\Eloquent;

use Botble\Media\Repositories\Interfaces\MediaFolderInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Str;

/**
 * @since 19/08/2015 07:45 AM
 */
class MediaFolderRepository extends RepositoriesAbstract implements MediaFolderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getFolderByParentId($folder_id, array $params = [], $withTrash = false)
    {
        $params = array_merge([
            'condition' => [],
        ], $params);

        if (!$folder_id) {
            $folder_id = null;
        }

        if ($folder_id != -1) {
            $this->model = $this->model->where('parent_id', '=', $folder_id);
        }

        if ($withTrash) {
            $this->model = $this->model->withTrashed();
        }

        return $this->advancedGet($params);
    }

    /**
     * {@inheritdoc}
     */
    public function createSlug($name, $parentId)
    {
        $slug = Str::slug($name);
        $index = 1;
        $baseSlug = $slug;
        while ($this->checkIfExists('slug', $slug, $parentId)) {
            $slug = $baseSlug . '-' . $index++;
        }

        return $slug;
    }

    /**
     * {@inheritdoc}
     */
    public function createName($name, $parentId)
    {
        $newName = $name;
        $index = 1;
        $baseSlug = $newName;
        while ($this->checkIfExists('name', $newName, $parentId)) {
            $newName = $baseSlug . '-' . $index++;
        }

        return $newName;
    }

    /**
     * @param $key
     * @param $value
     * @param $parentId
     * @return bool
     */
    protected function checkIfExists($key, $value, $parentId)
    {
        $count = $this->model->where($key, '=', $value)->where('parent_id', $parentId)->withTrashed();

        /**
         * @var Builder $count
         */
        $count = $count->count();

        return $count > 0;
    }

    /**
     * {@inheritdoc}
     */
    public function getBreadcrumbs($parentId, $breadcrumbs = [])
    {
        if ($parentId == 0) {
            return $breadcrumbs;
        }

        $folder = $this->getFirstByWithTrash(['id' => $parentId]);

        if (empty($folder)) {
            return $breadcrumbs;
        }

        $child = $this->getBreadcrumbs($folder->parent_id, $breadcrumbs);
        return array_merge($child, [
            [
                'id'   => $folder->id,
                'name' => $folder->name,
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getTrashed($parentId, array $params = [])
    {
        $params = array_merge([
            'where' => [],
        ], $params);
        $data = $this->model
            ->select('media_folders.*')
            ->where($params['where'])
            ->orderBy('media_folders.name', 'asc')
            ->onlyTrashed();

        /**
         * @var Builder $data
         */
        if ($parentId == 0) {
            $data->leftJoin('media_folders as mf_parent', 'mf_parent.id', '=', 'media_folders.parent_id')
                ->where(function ($query) {
                    /**
                     * @var Builder $query
                     */
                    $query
                        ->orWhere('media_folders.parent_id', '=', 0)
                        ->orWhere('mf_parent.deleted_at', '=', null);
                })
                ->withTrashed();
        } else {
            $data->where('media_folders.parent_id', '=', $parentId);
        }

        return $data->get();
    }

    /**
     * {@inheritdoc}
     */
    public function deleteFolder($folder_id, $force = false)
    {
        $child = $this->getFolderByParentId($folder_id, [], $force);
        foreach ($child as $item) {
            $this->deleteFolder($item->id, $force);
        }

        if ($force) {
            $this->forceDelete(['id' => $folder_id]);
        } else {
            $this->deleteBy(['id' => $folder_id]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAllChildFolders($parentId, $child = [])
    {
        if ($parentId == 0) {
            return $child;
        }

        $folders = $this->allBy(['parent_id' => $parentId]);

        if (!empty($folders)) {
            foreach ($folders as $folder) {
                $child[$parentId][] = $folder;
                return $this->getAllChildFolders($folder->id, $child);
            }
        }

        return $child;
    }

    /**
     * {@inheritdoc}
     */
    public function getFullPath($folder_id, $path = '/')
    {
        if ($folder_id == 0) {
            return $path;
        }

        $folder = $this->getFirstByWithTrash(['id' => $folder_id]);

        if (empty($folder)) {
            return $path;
        }

        $child = $this->getFullPath($folder->parent_id, $path);

        return rtrim($child, '/') . '/' . $folder->slug;
    }

    /**
     * {@inheritdoc}
     */
    public function restoreFolder($folder_id)
    {
        $child = $this->getFolderByParentId($folder_id, [], true);
        foreach ($child as $item) {
            $this->restoreFolder($item->id);
        }

        $this->restoreBy(['id' => $folder_id]);
    }

    /**
     * {@inheritdoc}
     */
    public function emptyTrash()
    {
        $folders = $this->model->onlyTrashed();

        /**
         * @var Builder $folders
         */
        $folders = $folders->get();
        foreach ($folders as $folder) {
            /**
             * @var \Eloquent $folder
             */
            $folder->forceDelete();
        }
        return true;
    }
}
