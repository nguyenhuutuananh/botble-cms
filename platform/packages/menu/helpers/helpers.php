<?php

use Botble\Menu\Repositories\Interfaces\MenuInterface;

if (!function_exists('get_all_menus')) {
    /**
     * @return mixed
     * @author Sang Nguyen
     */
    function get_all_menus()
    {
        return app(MenuInterface::class)->all();
    }
}
