<?php

namespace Botble\Revision\Providers;

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
     */
    public function boot()
    {
        add_filter(BASE_FILTER_REGISTER_CONTENT_TABS, [$this, 'addHistoryTab'], 55, 3);
        add_filter(BASE_FILTER_REGISTER_CONTENT_TAB_INSIDE, [$this, 'addHistoryContent'], 55, 3);
    }

    /**
     * @param $tabs
     * @param $screen
     * @return string
     * @author Sang Nguyen
     * @since 2.0
     * @throws \Throwable
     */
    public function addHistoryTab($tabs, $screen, $data = null)
    {
        if (in_array($screen, config('packages.revision.general.supported')) && !empty($data)) {
            return $tabs . view('packages.revision::history-tab')->render();
        }
        return $tabs;
    }

    /**
     * @param $tabs
     * @param $screen
     * @param \Eloquent $data
     * @return string
     * @author Sang Nguyen
     * @since 2.0
     * @throws \Throwable
     */
    public function addHistoryContent($tabs, $screen, $data = null)
    {
        if (in_array($screen, config('packages.revision.general.supported')) && !empty($data)) {
            return $tabs . view('packages.revision::history-content', ['model' => $data])->render();
        }
        return $tabs;
    }
}
