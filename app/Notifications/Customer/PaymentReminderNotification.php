<?php

namespace App\Notifications\Customer;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;

class PaymentReminderNotification extends Notification implements ShouldQueue
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
            ->subject('Friendly Reminder: We Miss Your Payment!')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('We noticed you have a pending transaction with us. Don’t worry, it happens to the best of us!')
            ->line('When you get a moment, please complete your payment.')
            // ->action('Pay Now', url('/'))
            ->line('Invoice ID: ' . $this->transaction->invoice_number)
            ->line('We appreciate your prompt attention to this matter.')
            ->line('Thank you for being a valued customer!')
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
