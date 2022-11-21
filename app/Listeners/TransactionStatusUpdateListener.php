<?php

namespace App\Listeners;

use App\Notifications\TransactionStatusUpdateNotication;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TransactionStatusUpdateListener
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $order = $event->order;
        if($order){
            $user= $order->user;
            $user->notify(new TransactionStatusUpdateNotication($order));
        }
    }
}
