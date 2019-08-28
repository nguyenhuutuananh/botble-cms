<?php

namespace Botble\Api\Providers;

use Barryvdh\Cors\HandleCors;
use Botble\Api\Http\Middleware\ForceJsonResponseMiddleware;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Laravel\Passport\Passport;

class ApiServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * @author Sang Nguyen
     */
    public function register()
    {
        /**
         * @var Router $router
         */
        $router = $this->app['router'];

        $router->pushMiddlewareToGroup('api', ForceJsonResponseMiddleware::class);
        $router->pushMiddlewareToGroup('api', HandleCors::class);
    }

    /**
     * @author Sang Nguyen
     */
    public function boot()
    {
        config([
            'auth.guards.api' => [
                'driver'   => 'passport',
                'provider' => 'users',
            ],
        ]);

        Passport::routes();

        Passport::tokensExpireIn(now()->addDays(15));

        Passport::refreshTokensExpireIn(now()->addDays(30));

        $this->setNamespace('packages/api')
            ->publishPublicFolder();

        $this->app->booted(function () {
            config([
                'apidoc.routes.0.match.prefixes' => ['api/*'],
            ]);
        });
    }
}
