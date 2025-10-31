<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Notifications\Customer\PaymentReminderNotification as PaymentReminder;
use Illuminate\Support\Facades\Notification;

class SendPaymentReminders extends Command
{
    protected $signature = 'send:payment-reminders';
    protected $description = 'Send payment reminders to customers with unpaid transactions';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $transactions = Transaction::where('transaction_status', '=', 'Waiting Payment')->get();

        foreach ($transactions as $transaction) {
            $user = $transaction->user;
            Notification::send($user, new PaymentReminder($transaction));
        }

        return 0;
    }
}
