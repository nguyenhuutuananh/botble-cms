<?php

namespace Botble\Widget\Facades;

use Illuminate\Support\Facades\Facade;

class AsyncFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'botble.async-widget';
    }
}
