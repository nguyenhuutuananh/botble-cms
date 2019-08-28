<?php

namespace Botble\Backup;

use Botble\Base\Interfaces\PluginInterface;
use File;

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
        $backup_path = storage_path('app/backup');
        if (File::isDirectory($backup_path)) {
            File::deleteDirectory($backup_path);
        }
    }
}
