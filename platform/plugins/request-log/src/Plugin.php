<?php

namespace Botble\RequestLog;

use Botble\Base\Interfaces\PluginInterface;
use Botble\Dashboard\Repositories\Interfaces\DashboardWidgetInterface;
use Schema;

class Plugin implements PluginInterface
{
    /**
     * @author Sang Nguyen
     */
    public static function activate()
    {
    }

    /**
     * @author Sang Nguyen
     */
    public static function deactivate()
    {
    }

    /**
     * @author Sang Nguyen
     */
    public static function remove()
    {
        Schema::dropIfExists('request_logs');
        app(DashboardWidgetInterface::class)->deleteBy(['name' => 'widget_request_errors']);
    }
}
