<?php

namespace Botble\Base\Interfaces;

/**
 * @deprecated since v3.6
 * Using Botble\PluginManagement\Abstracts\PluginOperationAbstract instead this interface
 */
interface PluginInterface
{
    public static function activate();

    public static function deactivate();

    public static function remove();
}
