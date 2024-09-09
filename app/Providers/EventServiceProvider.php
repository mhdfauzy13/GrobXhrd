<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\RoleCreated' => [
            'App\Listeners\AssignDefaultRole',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     * @return void
     */
    public function boot()
    {
        parent::boot();
        
        // Daftarkan event dan listener tambahan jika ada
    }
}
