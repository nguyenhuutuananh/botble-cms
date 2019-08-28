<?php

namespace Botble\Gallery\Listeners;

use Botble\Base\Events\UpdatedContentEvent;
use Exception;
use Gallery;

class UpdatedContentListener
{

    /**
     * Handle the event.
     *
     * @param UpdatedContentEvent $event
     * @return void
     * @author Sang Nguyen
     */
    public function handle(UpdatedContentEvent $event)
    {
        try {
            Gallery::saveGallery($event->screen, $event->request, $event->data);
        } catch (Exception $exception) {
            info($exception->getMessage());
        }
    }
}
