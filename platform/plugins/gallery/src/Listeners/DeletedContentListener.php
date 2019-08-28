<?php

namespace Botble\Gallery\Listeners;

use Botble\Base\Events\DeletedContentEvent;
use Exception;
use Gallery;

class DeletedContentListener
{

    /**
     * Handle the event.
     *
     * @param DeletedContentEvent $event
     * @return void
     * @author Sang Nguyen
     */
    public function handle(DeletedContentEvent $event)
    {
        try {
            Gallery::deleteGallery($event->screen, $event->data);
        } catch (Exception $exception) {
            info($exception->getMessage());
        }
    }
}
