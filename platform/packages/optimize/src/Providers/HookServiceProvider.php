<?php

namespace Botble\Optimize\Providers;

use Illuminate\Support\ServiceProvider;

class HookServiceProvider extends ServiceProvider
{
    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * @author Sang Nguyen
     */
    public function boot()
    {
        add_filter(BASE_FILTER_AFTER_SETTING_CONTENT, [$this, 'addSetting'], 27, 1);
    }

    /**
     * @param null $data
     * @return string
     * @throws \Throwable
     * @author Sang Nguyen
     */
    public function addSetting($data = null)
    {
        return $data . view('packages.optimize::setting')->render();
    }
}
