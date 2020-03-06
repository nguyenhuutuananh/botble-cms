<?php

namespace Botble\Captcha\Providers;

use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Captcha\Facades\CaptchaFacade;
use Botble\Captcha\Captcha;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class CaptchaServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->bind(Captcha::class, function () {
            return new Captcha(
                setting('captcha_secret', config('plugins.captcha.general.secret')),
                setting('captcha_site_key', config('plugins.captcha.general.site_key')),
                config('plugins.captcha.general.lang'),
                config('plugins.captcha.general.attributes', [])
            );
        });
        AliasLoader::getInstance()->alias('Captcha', CaptchaFacade::class);
    }

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $this->app['validator']->extend('captcha', function ($attribute, $value) {
            unset($attribute);
            $ip = $this->app['request']->getClientIp();

            return $this->app[Captcha::class]->verify($value, $ip);
        });

        if ($this->app->bound('form')) {
            $this->app['form']->macro('captcha', function ($name = null, array $attributes = []) {
                return $this->app['botble::no-captcha']->display($name, $attributes);
            });
        }

        $this->setNamespace('plugins/captcha')
            ->loadAndPublishConfigurations(['general'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations();

        $this->app->register(HookServiceProvider::class);
    }
}
