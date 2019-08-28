<?php

namespace Botble\Setting\Facades;

use Botble\Setting\Supports\SettingStore;
use Illuminate\Support\Facades\Facade;

class SettingFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     * @author Sang Nguyen
     */
    protected static function getFacadeAccessor()
    {
        return SettingStore::class;
    }
}
