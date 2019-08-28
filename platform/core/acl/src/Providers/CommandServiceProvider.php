<?php

namespace Botble\ACL\Providers;

use Botble\ACL\Commands\RebuildPermissionsCommand;
use Botble\ACL\Commands\SendUserBirthdayEmailCommand;
use Botble\ACL\Commands\UserCreateCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
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
        if ($this->app->runningInConsole()) {
            $this->commands([
                UserCreateCommand::class,
                SendUserBirthdayEmailCommand::class,
            ]);

            $this->app->booted(function () {
                $this->app->make(Schedule::class)
                    ->command(SendUserBirthdayEmailCommand::class)
                    ->daily();
            });
        }

        $this->commands([
            RebuildPermissionsCommand::class,
        ]);
    }
}
