<?php

namespace Botble\Backup\Providers;

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
        if (app()->environment('demo')) {
            add_filter(DASHBOARD_FILTER_ADMIN_NOTIFICATIONS, [$this, 'registerAdminAlert'], 5, 1);
        }

        add_filter(BASE_FILTER_AFTER_SETTING_CONTENT, [$this, 'addBackupSetting'], 199, 1);
    }

    /**
     * @param string $alert
     * @return string
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function registerAdminAlert($alert)
    {
        return $alert . view('plugins.backup::partials.admin-alert')->render();
    }

    /**
     * @param null $data
     * @return string
     * @throws \Throwable
     * @author Sang Nguyen
     */
    public function addBackupSetting($data = null)
    {
        return $data . view('plugins.backup::setting')->render();
    }
}
