<?php

namespace Botble\Base\Facades;

use Botble\Base\Supports\Assets\Assets;
use Illuminate\Support\Facades\Facade;

class AssetsFacade extends Facade
{

    /**
     * @return string
     * @author Sang Nguyen
     */
    protected static function getFacadeAccessor()
    {
        return Assets::class;
    }
}
