<?php

namespace Botble\Gallery\Listeners;

use Botble\Base\Events\CreatedContentEvent;
use Exception;
use Gallery;

class CreatedContentListener
{

    /**
     * Handle the event.
     *
     * @param CreatedContentEvent $event
     * @return void
     * @author Sang Nguyen
     */
    public function handle(CreatedContentEvent $event)
    {
        try {
            Gallery::saveGallery($event->screen, $event->request, $event->data);
        } catch (Exception $exception) {
            info($exception->getMessage());
        }
    }
}
