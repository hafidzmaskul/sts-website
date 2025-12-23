<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCustomerSubmissionNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $customer;

    /**
     * Create a new notification instance.
     */
    public function __construct($customer)
    {
        $this->customer = $customer;
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
            ->subject('New Customer Registration Submission')
            ->greeting('Hello Admin,')
            ->line('A new customer has submitted a registration request.')
            ->line('Name: ' . $this->customer->first_name . ' ' . $this->customer->last_name)
            ->line('Email: ' . $this->customer->email)
            ->line('Role Applied: ' . ucfirst($this->customer->role_applied))
            ->line('Please review the application in the admin panel.')
            ->action('Review Application', route('admin.customers.show', $this->customer->id));
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
