<?php

namespace Botble\Base\Supports\Assets;

use Botble\Assets\Assets as BaseAssets;
use Botble\Assets\HtmlBuilder;
use Botble\Base\Supports\Language;
use File;
use Illuminate\Config\Repository;
use Illuminate\Support\Str;

/**
 * Class Assets
 * @package Botble\Assets
 * @author Sang Nguyen
 * @since 22/07/2015 11:23 PM
 */
class Assets extends BaseAssets
{
    /**
     * @var array
     */
    protected $appModules = [];

    /**
     * Assets constructor.
     *
     * @param Repository $config
     * @param HtmlBuilder $htmlBuilder
     */
    public function __construct(Repository $config, HtmlBuilder $htmlBuilder)
    {
        parent::__construct($config, $htmlBuilder);

        $this->config = $config->get('core.base.assets');

        $this->scripts = $this->config['scripts'];

        $this->styles = $this->config['styles'];
    }

    /**
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * Add Module to current module
     *
     * @param array $modules
     * @return $this;
     * @author Sang Nguyen
     */
    public function addAppModule($modules): self
    {
        if (!is_array($modules)) {
            $modules = [$modules];
        }
        $this->appModules = array_merge($this->appModules, $modules);

        return $this;
    }

    /**
     * Get all modules in current module
     *
     * @return array
     * @author Sang Nguyen
     */
    public function getAppModules(): array
    {
        $modules = [];
        if (!empty($this->appModules)) {
            // get the final scripts need for page
            $this->appModules = array_unique($this->appModules);
            foreach ($this->appModules as $module) {
                if (($module = $this->getModule($module)) !== null) {
                    $modules[] = ['src' => $module, 'attributes' => []];
                }
            }
        }

        return $modules;
    }

    /**
     * Get a modules
     *
     * @param string $module : module's name
     * @return string
     */
    protected function getModule($module): ?string
    {
        $pathPrefix = public_path('vendor/core/js/app_modules/' . $module);

        $file = null;

        if (file_exists($pathPrefix . '.min.js')) {
            $file = $module . '.min.js';
        } elseif (file_exists($pathPrefix . '.js')) {
            $file = $module . '.js';
        }

        if ($file) {
            return '/vendor/core/js/app_modules/' . $file . $this->getBuildVersion();
        }

        return null;
    }

    /**
     * Get all admin themes
     *
     * @return array
     * @author Sang Nguyen
     */
    public function getThemes(): array
    {
        $themes = [];

        if (!File::isDirectory(public_path('vendor/core/css/themes'))) {
            return $themes;
        }

        foreach (File::files(public_path('vendor/core/css/themes')) as $file) {
            $name = '/vendor/core/css/themes/' . basename($file);
            if (!Str::contains($file, '.css.map')) {
                $themes[basename($file, '.css')] = $name;
            }
        }

        return $themes;
    }

    /**
     * @return array
     * @author Sang Nguyen
     */
    public function getAdminLocales(): array
    {
        $languages = [];
        $locales = scan_folder(resource_path('lang'));
        if (in_array('vendor', $locales)) {
            $locales = array_merge($locales, scan_folder(resource_path('lang/vendor')));
        }

        foreach ($locales as $locale) {
            if ($locale == 'vendor') {
                continue;
            }
            foreach (Language::getListLanguages() as $key => $language) {
                if (in_array($key, [$locale, str_replace('-', '_', $locale)]) ||
                    in_array($language[0], [$locale, str_replace('-', '_', $locale)])
                ) {
                    $languages[$locale] = [
                        'name' => $language[2],
                        'flag' => $language[4],
                    ];
                }
            }
        }

        return $languages;
    }

    /**
     * @param $module
     * @return null|string
     */
    public function getAppModuleItemToHtml($module): ?string
    {
        $src = $this->getModule($module);

        if (!$src) {
            return null;
        }

        return $this->htmlBuilder->script($src, ['class' => 'hidden'])->toHtml();
    }

    /**
     * @param array $lastStyles
     * @return string
     * @throws \Throwable
     */
    public function renderHeader($lastStyles = [])
    {
        do_action(BASE_ACTION_ENQUEUE_SCRIPTS);

        return parent::renderHeader($lastStyles);
    }

    /**
     * @return string
     * @throws \Throwable
     */
    public function renderFooter()
    {
        $bodyScripts = array_merge($this->getScripts(self::ASSETS_SCRIPT_POSITION_FOOTER), $this->getAppModules());

        return view('assets::footer', compact('bodyScripts'))->render();
    }
}
