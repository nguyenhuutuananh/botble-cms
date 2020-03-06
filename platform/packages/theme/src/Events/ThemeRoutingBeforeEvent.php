<?php

namespace Botble\Theme\Events;

use Botble\Base\Events\Event;
use Illuminate\Queue\SerializesModels;

class ThemeRoutingBeforeEvent extends Event
{
    use SerializesModels;

    /**
     * @var \Illuminate\Contracts\Foundation\Application|mixed
     */
    public $router;

    /**
     * ThemeRoutingBeforeEvent constructor.
     */
    public function __construct()
    {
        $this->router = app('router');
    }
}