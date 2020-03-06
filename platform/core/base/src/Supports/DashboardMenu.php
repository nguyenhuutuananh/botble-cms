<?php

namespace Botble\Base\Supports;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use RuntimeException;
use URL;

class DashboardMenu
{
    /**
     * Get all registered links from package
     * @var array
     */
    protected $links = [];

    /**
     * Add link
     * @param array $options
     * @return $this
     */
    public function registerItem(array $options): self
    {
        if (isset($options['children'])) {
            unset($options['children']);
        }

        $defaultOptions = [
            'id'          => null,
            'priority'    => 99,
            'parent_id'   => null,
            'name'        => null,
            'icon'        => null,
            'url'         => null,
            'children'    => [],
            'permissions' => [],
            'active'      => false,
        ];

        $options = array_merge($defaultOptions, $options);
        $id = $options['id'];

        if (!$id) {
            $calledClass = isset(debug_backtrace()[1]) ?
                debug_backtrace()[1]['class'] . '@' . debug_backtrace()[1]['function']
                :
                null;
            throw new RuntimeException('Menu id not specified: ' . $calledClass);
        }

        if (isset($this->links[$id]) && $this->links[$id]['name'] && !app()->runningInConsole()) {
            $calledClass = isset(debug_backtrace()[1]) ?
                debug_backtrace()[1]['class'] . '@' . debug_backtrace()[1]['function']
                :
                null;
            throw new RuntimeException('Menu id already exists: ' . $id . ' on class ' . $calledClass);
        }

        if (isset($this->links[$id])) {

            $options['children'] = array_merge($options['children'], $this->links[$id]['children']);
            $options['permissions'] = array_merge($options['permissions'], $this->links[$id]['permissions']);

            $this->links[$id] = array_replace($this->links[$id], $options);

            return $this;
        }

        if ($options['parent_id']) {
            if (!isset($this->links[$options['parent_id']])) {
                $this->links[$options['parent_id']] = $defaultOptions;
            }

            $this->links[$options['parent_id']]['children'][] = $options;

            $permissions = array_merge($this->links[$options['parent_id']]['permissions'], $options['permissions']);
            $this->links[$options['parent_id']]['permissions'] = $permissions;
        } else {
            $this->links[$id] = $options;
        }

        return $this;
    }

    /**
     * @param array|string $id
     * @return $this
     */
    public function removeItem($id, $parentId = null): self
    {
        $id = is_array($id) ? $id : func_get_args();
        foreach ($id as $item) {
            if (!$parentId) {
                Arr::forget($this->links, $item);
            } else {
                foreach ($this->links[$parentId]['children'] as $key => $child) {
                    if ($child['id'] === $item) {
                        Arr::forget($this->links[$parentId]['children'], $key);
                        break;
                    }
                }
            }
        }

        return $this;
    }

    /**
     * Rearrange links
     * @return Collection
     * @throws \Exception
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getAll(): Collection
    {
        $currentUrl = URL::full();

        $prefix = request()->route()->getPrefix();
        if (!$prefix || $prefix === config('core.base.general.admin_dir')) {
            $uri = explode('/', request()->route()->uri());
            $prefix = end($uri);
        }

        $routePrefix = '/' . $prefix;

        if (setting('cache_admin_menu_enable', true) && Auth::check()) {
            $cache_key = md5('cache-dashboard-menu-' . Auth::user()->getKey());
            if (!cache()->has($cache_key)) {
                $links = $this->links;
                cache()->forever($cache_key, $links);
            } else {
                $links = cache($cache_key);
            }
        } else {
            $links = $this->links;
        }

        foreach ($links as $key => &$link) {
            if (!Auth::user()->hasAnyPermission($link['permissions'])) {
                Arr::forget($links, $key);
                continue;
            }

            $link['active'] = $currentUrl == $link['url'] ||
                (Str::contains($link['url'], $routePrefix) && $routePrefix != '//');
            if (!count($link['children'])) {
                continue;
            }

            $link['children'] = collect($link['children'])->sortBy('priority')->toArray();

            foreach ($link['children'] as $sub_key => $sub_menu) {
                if (!Auth::user()->hasAnyPermission($sub_menu['permissions'])) {
                    Arr::forget($link['children'], $sub_key);
                    continue;
                }

                if ($currentUrl == $sub_menu['url'] ||
                    (Str::contains($sub_menu['url'], $routePrefix) && $routePrefix != '//')
                ) {
                    $link['children'][$sub_key]['active'] = true;
                    $link['active'] = true;
                }
            }
        }

        return collect($links)->sortBy('priority');
    }
}
