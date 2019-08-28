<?php

namespace Botble\Support\Providers;

use File;
use Illuminate\Support\ServiceProvider;

class SupportServiceProvider extends ServiceProvider
{
    /**
     * @author Sang Nguyen
     */
    public function register()
    {
        File::requireOnce(__DIR__ . '/../../helpers/common.php');
    }
}
