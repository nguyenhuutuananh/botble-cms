<?php

namespace Botble\Table\Providers;

use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Table\Facades\TableBuilderFacade;
use Botble\Table\TableBuilder;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class TableServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    public function register()
    {
        $this->app->bind('table-builder', function (Container $container) {
            return new TableBuilder($container);
        });

        $loader = AliasLoader::getInstance();
        $loader->alias('TableBuilder', TableBuilderFacade::class);
    }

    /**
     * Bootstrap the application events.
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->setNamespace('core/table')
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->publishAssetsFolder()
            ->publishPublicFolder();
    }
}
