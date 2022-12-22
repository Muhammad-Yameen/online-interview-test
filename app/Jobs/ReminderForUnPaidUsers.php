<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\User;
use App\Notifications\UnPaidInvoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReminderForUnPaidUsers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $users = User::whereHas('orders', function ($query) {
            $query->where('status', Order::UNPAID);
        })->get();
        if ($users->count()) {
            foreach ($users as $user) {
                $user->notify(new UnPaidInvoice);
            }
        }
    }
}
