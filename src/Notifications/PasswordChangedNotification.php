<?php

namespace Whozidis\HallOfFame\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected ?string $ip = null, protected ?string $ua = null)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject(__('Your password was changed'))
            ->greeting(__('Hello :name,', ['name' => $notifiable->name ?? '']))
            ->line(__('This is a security notification to let you know your password was recently changed.'))
            ->line(__('IP: :ip', ['ip' => (string) $this->ip]))
            ->line(__('Device: :ua', ['ua' => (string) $this->ua]))
            ->line(__('If this wasn\'t you, please reset your password immediately and contact support.'))
            ->action(__('Reset Password'), route('customer.password.reset'));
    }
}
