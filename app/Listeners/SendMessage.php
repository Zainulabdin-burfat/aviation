<?php

namespace App\Listeners;

use App\Events\MessageSent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendMessage implements ShouldQueue
{
    public function handle(MessageSent $event)
    {
        // Handle the event (e.g., save the message to the database)
    }
}
