<?php

namespace Botble\Contact;

use Botble\Base\Interfaces\PluginInterface;
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
        Schema::dropIfExists('contacts');
    }
}
