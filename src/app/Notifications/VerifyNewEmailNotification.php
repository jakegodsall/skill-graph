<?php

namespace App\Notifications;

use App\Mail\VerifyNewEmail;
use App\Models\Auth\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class VerifyNewEmailNotification extends Notification
{
    use Queueable;

    protected User $user;
    protected string $newEmail;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $newEmail)
    {
        $this->newEmail = $newEmail;
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
    public function toMail(object $notifiable)
    {
        $verificationUrl = url(route('email.change.verify', [
            'id' => $notifiable->id,
            'hash' => sha1($this->newEmail)
        ], false));

        return (new VerifyNewEmail($notifiable, $verificationUrl))->to($this->newEmail);
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
