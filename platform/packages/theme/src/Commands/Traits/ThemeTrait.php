<?php

namespace Botble\Theme\Commands\Traits;

trait ThemeTrait
{
    /**
     * Get the theme name.
     *
     * @return string
     */
    protected function getTheme()
    {
        return strtolower($this->argument('name'));
    }

    /**
     * Get root writable path.
     *
     * @param  string $path
     * @return string
     */
    protected function getPath($path)
    {
        $rootPath = theme_path();
        if ($this->option('path')) {
            $rootPath = $this->option('path');
        }

        return rtrim($rootPath, '/') . '/' . rtrim(ltrim(strtolower($this->getTheme()), '/'), '/') . '/' . $path;
    }
}