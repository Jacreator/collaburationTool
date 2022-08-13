<?php

namespace App\Subscribers\Models;

use App\Events\Models\User\UserCreatedEvent;
use App\Listeners\SendWelcomeEmail;
use Illuminate\Events\Dispatcher;

class UserSubscriber
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param object $event - event object
     * 
     * @return void
     */
    public function subscribe(Dispatcher $event)
    {

        $event->listen(
            UserCreatedEvent::class, SendWelcomeEmail::class
        );
    }
}