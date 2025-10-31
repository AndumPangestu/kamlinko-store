<?php

namespace App\Notifications\Customer;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;

class TransactionCompletedNotification extends Notification implements ShouldQueue
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
            ->subject('Transaction Complete')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('We are pleased to inform you that your transaction has been successfully completed.')
            ->line('Thank you for your purchase!')
            ->line('Invoice Number: ' . $this->transaction->invoice_number)
            ->line('We hope you enjoy your purchase.')
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
