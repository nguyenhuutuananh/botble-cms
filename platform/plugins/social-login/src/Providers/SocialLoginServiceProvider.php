<?php

namespace Botble\SocialLogin\Providers;

use Botble\Setting\Supports\SettingStore;
use Illuminate\Support\ServiceProvider;
use Botble\Base\Supports\Helper;
use Illuminate\Routing\Events\RouteMatched;
use Event;
use Botble\Base\Traits\LoadAndPublishDataTrait;

class SocialLoginServiceProvider extends ServiceProvider
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
        Helper::autoload(__DIR__ . '/../../helpers');
    }

    /**
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->setNamespace('plugins/social-login')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->publishPublicFolder()
            ->publishAssetsFolder();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-social-login',
                'priority'    => 5,
                'parent_id'   => 'cms-core-settings',
                'name'        => 'plugins/social-login::social-login.menu',
                'icon'        => null,
                'url'         => route('social-login.settings'),
                'permissions' => ['social-login.settings'],
            ]);
        });

        $config = $this->app->make('config');
        $setting = $this->app->make(SettingStore::class);

        $config->set([
            'services.facebook' => [
                'client_id'     => $setting->get('social_login_facebook_app_id', env('FACEBOOK_APP_ID')),
                'client_secret' => $setting->get('social_login_facebook_app_secret', env('FACEBOOK_APP_SECRET')),
                'redirect'      => route('auth.social.callback', 'facebook'),
            ],
            'services.google'   => [
                'client_id'     => $setting->get('social_login_google_app_id'),
                'client_secret' => $setting->get('social_login_google_app_secret'),
                'redirect'      => route('auth.social.callback', 'google'),
            ],
            'services.github'   => [
                'client_id'     => $setting->get('social_login_github_app_id'),
                'client_secret' => $setting->get('social_login_github_app_secret'),
                'redirect'      => route('auth.social.callback', 'github'),
            ],
        ]);

        $this->app->register(HookServiceProvider::class);
    }
}
