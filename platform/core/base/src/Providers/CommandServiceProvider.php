<?php

namespace Botble\Base\Providers;

use Botble\Base\Commands\ClearLogCommand;
use Botble\Base\Commands\Git\InstallHooks;
use Botble\Base\Commands\Git\PreCommitHook;
use Botble\Base\Commands\InstallCommand;
use Botble\Base\Commands\Make\ControllerMakeCommand;
use Botble\Base\Commands\Make\FormMakeCommand;
use Botble\Base\Commands\Make\RepositoryMakeCommand;
use Botble\Base\Commands\Make\RequestMakeCommand;
use Botble\Base\Commands\Make\RouteMakeCommand;
use Botble\Base\Commands\Make\TableMakeCommand;
use Botble\Base\Commands\PluginActivateCommand;
use Botble\Base\Commands\PluginAssetsPublishCommand;
use Botble\Base\Commands\PluginCreateCommand;
use Botble\Base\Commands\PluginDeactivateCommand;
use Botble\Base\Commands\PluginListCommand;
use Botble\Base\Commands\PluginRemoveCommand;
use Botble\Base\Commands\TestSendMailCommand;
use Botble\Base\Commands\TruncateTablesCommand;
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
                InstallCommand::class,
                InstallHooks::class,
                PreCommitHook::class,
                TableMakeCommand::class,
                ControllerMakeCommand::class,
                RouteMakeCommand::class,
                RequestMakeCommand::class,
                FormMakeCommand::class,
                RepositoryMakeCommand::class,
                TestSendMailCommand::class,
                PluginAssetsPublishCommand::class,
            ]);
        }

        $this->commands([
            PluginCreateCommand::class,
            PluginActivateCommand::class,
            PluginDeactivateCommand::class,
            PluginRemoveCommand::class,
            PluginListCommand::class,
            ClearLogCommand::class,
            TruncateTablesCommand::class,
        ]);
    }
}
