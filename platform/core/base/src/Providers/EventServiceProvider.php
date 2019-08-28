<?php

namespace Botble\Base\Providers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\SendMailEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Listeners\BeforeEditContentListener;
use Botble\Base\Listeners\CreatedContentListener;
use Botble\Base\Listeners\DeletedContentListener;
use Botble\Base\Listeners\SendMailListener;
use Botble\Base\Listeners\UpdatedContentListener;
use Event;
use File;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     * @author Sang Nguyen
     */
    protected $listen = [
        SendMailEvent::class          => [
            SendMailListener::class,
        ],
        CreatedContentEvent::class    => [
            CreatedContentListener::class,
        ],
        UpdatedContentEvent::class    => [
            UpdatedContentListener::class,
        ],
        DeletedContentEvent::class    => [
            DeletedContentListener::class,
        ],
        BeforeEditContentEvent::class => [
            BeforeEditContentListener::class,
        ],
    ];

    /** Boot the service provider.
     * @return void
     * @author Sang Nguyen
     */
    public function boot()
    {
        parent::boot();

        Event::listen(['cache:cleared'], function () {
            File::delete(storage_path('cache_keys.json'));
            File::delete(storage_path('settings.json'));
        });
    }
}
