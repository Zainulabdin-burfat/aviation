<?php

namespace App\Listeners;

use App\Events\MessageSent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendMessage implements ShouldQueue
{
    public function handle(MessageSent $event)
    {
        //
    }
}
