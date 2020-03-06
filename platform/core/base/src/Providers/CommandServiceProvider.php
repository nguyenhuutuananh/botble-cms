<?php

namespace Botble\Base\Providers;

use Botble\Base\Commands\ClearLogCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    public function boot()
    {
        $this->commands([
            ClearLogCommand::class,
        ]);
    }
}
