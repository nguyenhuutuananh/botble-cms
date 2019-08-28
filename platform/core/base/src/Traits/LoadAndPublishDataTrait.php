<?php

namespace Botble\Base\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

/**
 * Trait LoadAndPublishDataTrait
 * @package Botble\Base\Traits
 * @mixin ServiceProvider
 */
trait LoadAndPublishDataTrait
{
    /**
     * @var string
     */
    protected $namespace = null;

    /**
     * @var string
     */
    protected $basePath = null;

    /**
     * @param $namespace
     * @return $this
     */
    public function setNamespace($namespace): self
    {
        $this->namespace = ltrim(rtrim($namespace, '/'), '/');
        return $this;
    }

    /**
     * @param $isInConsole
     * @return $this
     * @deprecated
     */
    public function setIsInConsole($isInConsole): self
    {
        return $this;
    }

    /**
     * @param $path
     * @return $this
     */
    public function setBasePath($path): self
    {
        $this->basePath = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getBasePath(): string
    {
        return $this->basePath ?? platform_path();
    }

    /**
     * Publish the given configuration file name (without extension) and the given module
     * @param $file_names
     * @return $this
     */
    public function loadAndPublishConfigurations($file_names): self
    {
        if (!is_array($file_names)) {
            $file_names = [$file_names];
        }
        foreach ($file_names as $file_name) {
            $this->mergeConfigFrom($this->getConfigFilePath($file_name), $this->getDotedNamespace() . '.' . $file_name);
            if ($this->app->runningInConsole()) {
                $this->publishes([
                    $this->getConfigFilePath($file_name) => config_path($this->getDashedNamespace() . '/' . $file_name . '.php'),
                ], 'config');
            }
        }

        return $this;
    }

    /**
     * Publish the given configuration file name (without extension) and the given module
     * @param $file_names
     * @return $this
     */
    public function loadRoutes($file_names = ['web']): self
    {
        if (!is_array($file_names)) {
            $file_names = [$file_names];
        }
        foreach ($file_names as $file_name) {
            $this->loadRoutesFrom($this->getRouteFilePath($file_name));
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function loadAndPublishViews(): self
    {
        $this->loadViewsFrom($this->getViewsPath(), $this->getDotedNamespace());
        if ($this->app->runningInConsole()) {
            $this->publishes([$this->getViewsPath() => resource_path('views/vendor/' . $this->getDashedNamespace())], 'views');
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function loadAndPublishTranslations(): self
    {
        $this->loadTranslationsFrom($this->getTranslationsPath(), $this->getDashedNamespace());
        if ($this->app->runningInConsole()) {
            $this->publishes([$this->getTranslationsPath() => resource_path('lang/vendor/' . $this->getDashedNamespace())], 'lang');
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function loadMigrations(): self
    {
        $this->loadMigrationsFrom($this->getMigrationsPath());
        return $this;
    }

    /**
     * @return $this
     */
    public function publishAssetsFolder(): self
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([$this->getAssetsPath() => resource_path('assets/' . $this->getDashedNamespace())], 'assets');
        }

        return $this;
    }

    /**
     * @param null $path
     * @return $this
     */
    public function publishPublicFolder($path = null): self
    {
        if ($this->app->runningInConsole()) {
            if (empty($path)) {
                $path = Str::contains($this->getDotedNamespace(), 'plugins.') ? 'vendor/core/' . $this->getDashedNamespace() : 'vendor/core';
            }
            $this->publishes([$this->getPublicPath() => public_path($path)], 'public');
        }

        return $this;
    }

    /**
     * Get path of the give file name in the given module
     * @param string $file
     * @return string
     */
    protected function getConfigFilePath($file): string
    {
        return $this->getBasePath() . $this->getDashedNamespace() . '/config/' . $file . '.php';
    }

    /**
     * @param $file
     * @return string
     */
    protected function getRouteFilePath($file): string
    {
        return $this->getBasePath() . $this->getDashedNamespace() . '/routes/' . $file . '.php';
    }

    /**
     * @return string
     */
    protected function getViewsPath(): string
    {
        return $this->getBasePath() . $this->getDashedNamespace() . '/resources/views/';
    }

    /**
     * @return string
     */
    protected function getTranslationsPath(): string
    {
        return $this->getBasePath() . $this->getDashedNamespace() . '/resources/lang/';
    }

    /**
     * @return string
     */
    protected function getMigrationsPath(): string
    {
        return $this->getBasePath() . $this->getDashedNamespace() . '/database/migrations/';
    }

    /**
     * @return string
     */
    protected function getAssetsPath(): string
    {
        return $this->getBasePath() . $this->getDashedNamespace() . '/resources/assets/';
    }

    /**
     * @return string
     */
    protected function getPublicPath(): string
    {
        return $this->getBasePath() . $this->getDashedNamespace() . '/public/';
    }

    /**
     * @return string
     */
    protected function getDotedNamespace(): string
    {
        return str_replace('/', '.', $this->namespace);
    }

    /**
     * @return string
     */
    protected function getDashedNamespace(): string
    {
        return str_replace('.', '/', $this->namespace);
    }

    /**
     * @return string
     */
    protected function getModuleName(): string
    {
        return Arr::last(explode('/', $this->getDashedNamespace()));
    }
}