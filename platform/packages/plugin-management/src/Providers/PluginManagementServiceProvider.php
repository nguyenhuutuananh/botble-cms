<?php

namespace Botble\PluginManagement\Providers;

use Botble\Base\Supports\Helper;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Composer\Autoload\ClassLoader;
use Event;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Schema;

class PluginManagementServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Exception
     */
    public function boot()
    {
        $this->setNamespace('packages/plugin-management')
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes(['web']);

        Helper::autoload(__DIR__ . '/../../helpers');

        if (check_database_connection() && Schema::hasTable('settings')) {
            $plugins = get_active_plugins();
            if (!empty($plugins) && is_array($plugins)) {
                $loader = new ClassLoader;
                $providers = [];
                $namespaces = [];
                if (cache()->has('plugin_namespaces') && cache()->has('plugin_providers')) {
                    $providers = cache('plugin_providers');
                    if (!is_array($providers) || empty($providers)) {
                        $providers = [];
                    }

                    $namespaces = cache('plugin_namespaces');

                    if (!is_array($namespaces) || empty($namespaces)) {
                        $namespaces = [];
                    }
                }

                if (empty($namespaces) || empty($providers)) {
                    foreach ($plugins as $plugin) {
                        if (empty($plugin)) {
                            continue;
                        }

                        $pluginPath = plugin_path($plugin);

                        if (!File::exists($pluginPath . '/plugin.json')) {
                            continue;
                        }
                        $content = get_file_data($pluginPath . '/plugin.json');
                        if (!empty($content)) {
                            if (Arr::has($content, 'namespace') && !class_exists($content['provider'])) {
                                $namespaces[$plugin] = $content['namespace'];
                            }

                            $providers[] = $content['provider'];
                        }
                    }
                    cache()->forever('plugin_namespaces', $namespaces);
                    cache()->forever('plugin_providers', $providers);
                }

                foreach ($namespaces as $key => $namespace) {
                    $loader->setPsr4($namespace, plugin_path($key . '/src'));
                }

                $loader->register();

                foreach ($providers as $provider) {
                    if (!class_exists($provider)) {
                        continue;
                    }

                    $this->app->register($provider);
                }
            }
        }

        $this->app->register(CommandServiceProvider::class);
        $this->app->register(HookServiceProvider::class);

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id'          => 'cms-core-plugins',
                    'priority'    => 997,
                    'parent_id'   => null,
                    'name'        => 'core/base::layouts.plugins',
                    'icon'        => 'fa fa-plug',
                    'url'         => route('plugins.index'),
                    'permissions' => ['plugins.index'],
                ]);
        });

    }
}
