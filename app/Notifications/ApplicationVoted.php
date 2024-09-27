<?php

namespace App\Notifications;

use App\Channels\Pinpoint\PinpointSmsMessage;
use App\Models\Vote;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationVoted extends Notification
{
    use Queueable;

    public $vote;

    /**
     * Create a new notification instance.
     *
     * @param  Vote  $vote
     * @return void
     */
    public function __construct(Vote $vote)
    {
        $this->vote = $vote;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'sms'];
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
                    ->subject('Application '.$this->vote->application->control_number)
                    ->line($this->vote->user->name.' casted a vote on this application.')
                    ->action('Proceed to ATC Portal', route('applications.show', ['application' => $this->vote->application->id]))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the SMS representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \App\Channels\Messages\SmsMessage
     */
    public function toSms($notifiable)
    {
        return (new PinpointSmsMessage())
            ->create($this->vote->user->name.' casted a vote on Application '.$this->vote->application->control_number.'.');
    }
}
