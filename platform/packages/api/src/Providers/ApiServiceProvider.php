<?php

namespace Botble\Api\Providers;

use Barryvdh\Cors\HandleCors;
use Botble\Api\Http\Middleware\ForceJsonResponseMiddleware;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;

class ApiServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    public function register()
    {
        /**
         * @var Router $router
         */
        $router = $this->app['router'];

        $router->pushMiddlewareToGroup('api', ForceJsonResponseMiddleware::class);
        $router->pushMiddlewareToGroup('api', HandleCors::class);
    }

    public function boot()
    {
        $this->setNamespace('packages/api')
            ->publishPublicFolder();

        $this->app->booted(function () {
            config([
                'apidoc.routes.0.match.prefixes' => ['api/*'],
                'apidoc.routes.0.apply.headers'  => [
                    'Authorization' => 'Bearer {token}',
                    'Api-Version'   => 'v1',
                ],
            ]);
        });
    }
}
