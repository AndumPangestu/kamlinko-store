<?php

namespace App\Notifications\Customer;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;

class DeliveryConfirmedNotification extends Notification implements ShouldQueue
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
            ->subject('Your Delivery Details Have Been Confirmed')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('We are pleased to inform you that your delivery details have been confirmed.')
            ->line('Invoice Number: ' . $this->transaction->invoice_number)
            ->line('Delivery Number: ' . $this->transaction->tracking_number)
            ->line('Your package will be sent shortly. Thank you for shopping with us.')
            ->line('If you have any questions or need further assistance, please do not hesitate to contact us on WhatsApp.')
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
