<?php

namespace Botble\RequestLog\Events;

use Event;
use Illuminate\Queue\SerializesModels;

class RequestHandlerEvent extends Event
{
    use SerializesModels;

    /**
     * @var mixed
     */
    public $code;

    /**
     * RequestHandlerEvent constructor.
     * @param int $code
     */
    public function __construct($code)
    {
        $this->code = $code;
    }
}
