<?php

namespace Botble\Base\Facades;

use Botble\Base\Supports\Filter;
use Illuminate\Support\Facades\Facade;

/**
 * Class FilterFacade
 * @package Botble\Base
 */
class FilterFacade extends Facade
{

    /**
     * @return string
     * @author Sang Nguyen
     * @since 2.1
     */
    protected static function getFacadeAccessor()
    {
        return Filter::class;
    }
}
