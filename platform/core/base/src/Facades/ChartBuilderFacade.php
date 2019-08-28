<?php

namespace Botble\Base\Facades;

use Illuminate\Support\Facades\Facade;

class ChartBuilderFacade extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'chart-builder';
    }
}