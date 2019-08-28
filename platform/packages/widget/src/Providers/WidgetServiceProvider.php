<?php

namespace Botble\Widget\Providers;

use Botble\Base\Supports\Helper;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Widget\Commands\WidgetCreateCommand;
use Botble\Widget\Commands\WidgetRemoveCommand;
use Botble\Widget\Facades\AsyncFacade;
use Botble\Widget\Facades\WidgetFacade;
use Botble\Widget\Facades\WidgetGroupFacade;
use Botble\Widget\Factories\AsyncWidgetFactory;
use Botble\Widget\Factories\WidgetFactory;
use Botble\Widget\Misc\LaravelApplicationWrapper;
use Botble\Widget\Models\Widget;
use Botble\Widget\Repositories\Caches\WidgetCacheDecorator;
use Botble\Widget\Repositories\Eloquent\WidgetRepository;
use Botble\Widget\Repositories\Interfaces\WidgetInterface;
use Botble\Widget\WidgetGroupCollection;
use Botble\Widget\Widgets\Text;
use Event;
use File;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;
use WidgetGroup;

class WidgetServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(WidgetInterface::class, function () {
            return new WidgetCacheDecorator(new WidgetRepository(new Widget));
        });

        $this->app->bind('botble.widget', function () {
            return new WidgetFactory(new LaravelApplicationWrapper);
        });

        $this->app->bind('botble.async-widget', function () {
            return new AsyncWidgetFactory(new LaravelApplicationWrapper);
        });

        $this->app->singleton('botble.widget-group-collection', function () {
            return new WidgetGroupCollection(new LaravelApplicationWrapper);
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                WidgetCreateCommand::class,
                WidgetRemoveCommand::class,
            ]);
        }

        $this->app->alias('botble.widget', WidgetFactory::class);
        $this->app->alias('botble.async-widget', AsyncWidgetFactory::class);
        $this->app->alias('botble.widget-group-collection', WidgetGroupCollection::class);

        AliasLoader::getInstance()->alias('Widget', WidgetFacade::class);
        AliasLoader::getInstance()->alias('AsyncWidget', AsyncFacade::class);
        AliasLoader::getInstance()->alias('WidgetGroup', WidgetGroupFacade::class);

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->setNamespace('packages/widget')
            ->loadAndPublishConfigurations(['general', 'permissions'])
            ->loadRoutes()
            ->loadMigrations()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->publishAssetsFolder()
            ->publishPublicFolder();

        WidgetGroup::setGroup([
            'id'          => 'primary_sidebar',
            'name'        => 'Primary sidebar',
            'description' => 'This is primary sidebar section',
        ]);

        register_widget(Text::class);

        $widget_path = theme_path(setting('theme') . '/widgets');
        $widgets = scan_folder($widget_path);
        if (!empty($widgets) && is_array($widgets)) {
            foreach ($widgets as $widget) {
                $registration = $widget_path . '/' . $widget . '/registration.php';
                if (File::exists($registration)) {
                    File::requireOnce($registration);
                }
            }
        }

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id'          => 'cms-core-widget',
                    'priority'    => 3,
                    'parent_id'   => 'cms-core-appearance',
                    'name'        => 'core/base::layouts.widgets',
                    'icon'        => null,
                    'url'         => route('widgets.list'),
                    'permissions' => ['widgets.list'],
                ]);

            admin_bar()->registerLink('Widget', route('widgets.list'), 'appearance');
        });
    }
}
