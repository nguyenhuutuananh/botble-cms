<?php

namespace Botble\Base\Listeners;

use Botble\Base\Events\BeforeEditContentEvent;
use Exception;

class BeforeEditContentListener
{

    /**
     * Handle the event.
     *
     * @param BeforeEditContentEvent $event
     * @return void
     * @author Sang Nguyen
     */
    public function handle(BeforeEditContentEvent $event)
    {
        try {
            do_action(BASE_ACTION_BEFORE_EDIT_CONTENT, $event->screen, $event->request, $event->data);
        } catch (Exception $exception) {
            info($exception->getMessage());
        }
    }
}
