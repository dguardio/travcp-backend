<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BookExperience extends Notification
{
    use Queueable;

    private $full_name;
    private $experience_url;
    private $user_bookings_url;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($booking)
    {
        $this->booking = $booking;
        $this->url = config('app.frontend');
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
                    ->subject('Your have a new Booking From TravCp')
                    ->line('You have made a booking on TravCp!')
                    ->action('View Booking', url($this->url.'/experience/'.$this->booking->experience->id.'/'.$this->booking->experience->slug));
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
            "message" => "you have a new booking!",
            "call-to-action" => "view booking",
            "url" => $this->url.'/experience/'.$this->booking->experience->id.'/'.$this->booking->experience->slug,
            'action_link' => $this->url.'/experience/'.$this->booking->experience->id.'/'.$this->booking->experience->slug
        ];
    }
}
