<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class IsBooked extends Notification
{
    use Queueable;

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
            ->line('You just got booked on TravCp!')
            ->action('View Booking', url($this->url.'/experience/'.$this->booking->experience->id.'/'.$this->booking->experience->slug));
    }

    /**'/experience/'+ event.id + '/' + event.title.toString().toLowerCase().replace( /\s/g, '-')"
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            "message" => "you have just got booked!",
            "call-to-action" => "view booking",
            "url" => $this->url.'/experience/'.$this->booking->experience->id.'/'.$this->booking->experience->slug,
            'action_link' => $this->url.'/experience/'.$this->booking->experience->id.'/'.$this->booking->experience->slug
        ];
    }
}
