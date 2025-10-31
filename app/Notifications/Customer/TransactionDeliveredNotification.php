<?php

namespace App\Notifications\Customer;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;

class TransactionDeliveredNotification extends Notification implements ShouldQueue
{
    use Queueable, InteractsWithQueue;

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
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Order Has Been Delivered')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('We are pleased to inform you that your order has been successfully delivered.')
            ->line('Invoice Number: ' . $this->transaction->invoice_number)
            ->line('Thank you for shopping with us. We hope you enjoy your purchase.')
            ->line('If you have any questions or need further assistance, please do not hesitate to contact our support team.')
            ->salutation('Best regards, Kamlinko Store.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
