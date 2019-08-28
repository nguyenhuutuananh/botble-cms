<?php

namespace Botble\Base\Facades;

use Botble\Base\Supports\Action;
use Illuminate\Support\Facades\Facade;

/**
 * Class ActionFacade
 * @package Botble\Base
 */
class ActionFacade extends Facade
{

    /**
     * @return string
     * @author Sang Nguyen
     * @since 2.1
     */
    protected static function getFacadeAccessor()
    {
        return Action::class;
    }
}
