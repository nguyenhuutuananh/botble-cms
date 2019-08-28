<?php

namespace Botble\Base\Providers;

use Breadcrumbs;
use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Route;
use URL;

class BreadcrumbsServiceProvider extends ServiceProvider
{
    /**
     * @throws \DaveJamesMiller\Breadcrumbs\Exceptions\DuplicateBreadcrumbException
     * @author Sang Nguyen
     */
    public function boot()
    {
        Breadcrumbs::register('', function (BreadcrumbsGenerator $breadcrumbs) {
            $breadcrumbs->push('', '');
        });

        Breadcrumbs::register('dashboard.index', function (BreadcrumbsGenerator $breadcrumbs) {
            $breadcrumbs->push(trans('core/base::layouts.dashboard'), route('dashboard.index'));
        });

        /**
         * Register breadcrumbs based on menu stored in session
         * @author Sang Nguyen
         */
        Breadcrumbs::register('main', function (BreadcrumbsGenerator $breadcrumbs, $defaultTitle = null) {
            $prefix = '/' . ltrim($this->app->make('request')->route()->getPrefix(), '/');
            $url = URL::current();
            $site_title = setting('admin_title', config('core.base.general.base_name'));
            $arMenu = dashboard_menu()->getAll();
            if (Route::currentRouteName() != 'dashboard.index') {
                $breadcrumbs->parent('dashboard.index');
            }
            $found = false;
            foreach ($arMenu as $menuCategory) {
                if (($url == $menuCategory->url || (Str::contains($menuCategory->url, $prefix) && $prefix != '//')) && !empty($menuCategory->name)) {
                    $found = true;
                    $breadcrumbs->push(trans($menuCategory->name), $url);
                    if ($defaultTitle != trans($menuCategory->name) && $defaultTitle != $site_title) {
                        $breadcrumbs->push($defaultTitle, $url);
                    }
                    break;
                }
            }
            if (!$found) {
                foreach ($arMenu as $menuCategory) {
                    if (!$menuCategory->children->isEmpty()) {
                        foreach ($menuCategory->children as $menuItem) {
                            if (($url == $menuItem->url || (Str::contains($menuItem->url, $prefix) && $prefix != '//')) && !empty($menuItem->name)) {
                                $found = true;
                                $breadcrumbs->push(trans($menuCategory->name), $menuCategory->url);
                                $breadcrumbs->push(trans($menuItem->name), $menuItem->url);
                                if ($defaultTitle != trans($menuItem->name) && $defaultTitle != $site_title) {
                                    $breadcrumbs->push($defaultTitle, $url);
                                }
                                break;
                            }
                        }
                    }
                }
            }

            if (!$found) {
                $breadcrumbs->push($defaultTitle, $url);
            }
        });
    }
}
