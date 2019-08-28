<?php

namespace Botble\Theme\Providers;

use Botble\Base\Supports\Collection;
use Botble\Dashboard\Supports\DashboardWidgetInstance;
use Illuminate\Support\ServiceProvider;

class HookServiceProvider extends ServiceProvider
{
    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Boot the service provider.
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function boot()
    {
        add_filter(DASHBOARD_FILTER_ADMIN_LIST, [$this, 'addStatsWidgets'], 29, 2);
    }

    /**
     * @param array $widgets
     * @param Collection $widget_settings
     * @return array
     * @throws \Throwable
     * @author Sang Nguyen
     */
    public function addStatsWidgets($widgets, $widgetSettings)
    {
        $themes = count(scan_folder(theme_path()));

        $widget = new DashboardWidgetInstance();

        $widget->type = 'stats';
        $widget->permission = 'theme.list';
        $widget->key = 'widget_total_themes';
        $widget->title = trans('packages/theme::theme.theme');
        $widget->icon = 'fa fa-paint-brush';
        $widget->color = '#e7505a';
        $widget->statsTotal = $themes;
        $widget->route = route('theme.list');

        return $widget->init($widgets, $widgetSettings);
    }
}
