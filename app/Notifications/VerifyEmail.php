<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmail extends Notification
{
    use Queueable;

    private $user_id;
    private $token;
    private $url;
    /**
     * Create a new notification instance.
     *
     * @param $token
     * @param $user_id
     */
    public function __construct($token, $user_id)
    {
        $this->token = $token;
        $this->user_id = $user_id;
        $this->url = config('app.frontend') || "http://localhost:8080";
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
                    ->line('Welcome To Travcp, you need to verify your email address')
                    ->action('Verify Email', url($this->url.'/verify?token='.$this->token.'&user_id='.$this->user_id))
                    ->line('Thank you for your cooperation!');
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
            "message" => 'Welcome To Travcp, you need to verify your email address',
            'action_link' => $this->url.'/verify?token='.$this->token.'&user_id='.$this->user_id,
            'ending' => 'Thank you for your cooperation!'
        ];
    }
}
