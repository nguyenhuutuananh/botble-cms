<?php

namespace Botble\Base\Facades;

use Botble\Base\MetaBox;
use Illuminate\Support\Facades\Facade;

/**
 * Class MetaBoxFacade
 * @package Botble\Base
 */
class MetaBoxFacade extends Facade
{

    /**
     * @return string
     * @author Sang Nguyen
     * @since 2.2
     */
    protected static function getFacadeAccessor()
    {
        return MetaBox::class;
    }
}
