<?php

namespace App\Console\Commands;

use App\Jobs\ReminderForUnPaidUsers;
use App\Models\User;
use App\Notifications\UnPaidInvoice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class NotifyUnPaidUsersForPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:unpaid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is notifucation for invoice unpaid users';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        dispatch(new ReminderForUnPaidUsers);
    }
}
