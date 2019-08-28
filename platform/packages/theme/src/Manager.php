<?php

namespace Botble\Theme;

use File;

class Manager
{
    /**
     * @var array
     */
    protected $themes = [];

    /**
     * Construct the class
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function __construct()
    {
        $this->registerTheme(self::getAllThemes());
    }

    /**
     * @return array
     * @author Sang Nguyen
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getAllThemes()
    {
        $themes = [];
        $themePath = theme_path();
        foreach (scan_folder($themePath) as $folder) {
            $theme = get_file_data($themePath . DIRECTORY_SEPARATOR . $folder . '/theme.json');
            if (!empty($theme)) {
                $themes[$folder] = $theme;
            }
        }
        return $themes;
    }

    /**
     * @param $theme
     * @return void
     * @author Sang Nguyen
     */
    public function registerTheme($theme)
    {
        if (!is_array($theme)) {
            $theme = [$theme];
        }
        $this->themes = array_merge_recursive($this->themes, $theme);
    }

    /**
     * @return array
     * @author Sang Nguyen
     */
    public function getThemes()
    {
        return $this->themes;
    }
}
