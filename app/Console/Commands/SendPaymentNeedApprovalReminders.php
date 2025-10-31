<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\Admin\PaymentNeedApprovalNotification;
use Illuminate\Support\Facades\Notification;

use Illuminate\Support\Facades\Log;

class SendPaymentNeedApprovalReminders extends Command
{
    protected $signature = 'send:payment-need-approval-reminders';
    protected $description = 'Send transactions\' payments that need approval from admin';
    public function __construct()
    {
        parent::__construct();
    }
    public function handle()
    {
        Log::info('Running SendPaymentNeedApprovalReminders command');
        $transactions = Transaction::where('transaction_status', 'Payment Received')->get();
        $admins = User::whereIn('role', ['admin', 'superadmin'])->get();

        foreach ($transactions as $transaction) {
            Notification::send($admins, new PaymentNeedApprovalNotification($transaction));
        }
        return 0;
    }
}
