<?php

namespace Botble\Menu\Facades;

use Botble\Menu\Menu;
use Illuminate\Support\Facades\Facade;

class MenuFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     * @author Sang Nguyen
     */
    protected static function getFacadeAccessor()
    {
        return Menu::class;
    }
}
