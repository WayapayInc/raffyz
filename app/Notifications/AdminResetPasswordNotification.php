<?php

namespace App\Notifications;

use Filament\Auth\Notifications\ResetPassword as FilamentResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class AdminResetPasswordNotification extends FilamentResetPassword
{
    /**
     * Get the mail representation of the notification.
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('notifications.subject'))
            ->greeting(__('notifications.greeting', ['name' => $notifiable->name]))
            ->line(__('notifications.intro'))
            ->action(__('notifications.action'), $this->resetPasswordUrl)
            ->line(__('notifications.expiry'))
            ->line(__('notifications.outro'));
    }
}
