<?php

namespace Botble\Base\Facades;

use Botble\Base\Supports\MailVariable;
use Illuminate\Support\Facades\Facade;

class MailVariableFacade extends Facade
{

    /**
     * @return string
     * @author Sang Nguyen
     * @since 3.2
     */
    protected static function getFacadeAccessor()
    {
        return MailVariable::class;
    }
}
