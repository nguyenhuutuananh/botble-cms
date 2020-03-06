<?php

namespace Botble\PluginManagement\Abstracts;

abstract class PluginOperationAbstract
{
    public static function activate()
    {
        // Run when activating a plugin
    }

    public static function deactivate()
    {
        // Run when deactivating a plugin
    }

    public static function remove()
    {
        // Run when remove a plugin
    }
}