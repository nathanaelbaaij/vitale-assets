<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;

class ResetPassword extends ResetPasswordNotification
{
    use Queueable;

    public $token;

    /**
     * ResetPassword constructor.
     * @param $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Wachtwoord opnieuw instellen')
            ->line('Je wilt jouw wachtwoord op ' . config('app.name') . ' opnieuw instellen.')
            ->line('Om zeker te zijn dat jij degene bent die het wachtwoord wil wijzigen, verzoeken wij je op onderstaande link te klikken.')
            ->action(('Wachtwoord resetten'), url(config('app.url').route('password.reset', $this->token, false)))
            ->line('Als u niet om een wachtwoord reset heeft gevraagd, is er geen verdere actie nodig.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
