<?php

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;

class PaymentNeedApprovalNotification extends Notification
{
    // use Queueable, InteractsWithQueue;

    private $transaction;
    /**
     * Create a new notification instance.
     */
    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'transaction',
            'transaction_id' => $this->transaction->id,
            'transaction_status' => $this->transaction->transaction_status,
            'customer_name' => $this->transaction->user->name,
            'updated_at' => $this->transaction->updated_at,
            'message' => 'A new payment transaction, #' . $this->transaction->invoice_number . ', is pending approval.',
            'action_url' => route('admin.transaction-management.view-transaction-details', $this->transaction->id),
            'action_label' => 'View Transaction', // This can be dynamic based on the notification type
        ];
    }
}
