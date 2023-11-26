<?php

namespace App\Listeners;

use App\Events\NewMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendMessageNotification implements ShouldQueue
{
    public function handle(NewMessage $event)
    {
        // Notification logic here (e.g., sending a push notification, updating UI)
        // You can use Laravel's built-in notification system or other approaches.
    }
}
