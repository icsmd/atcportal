<?php

namespace App\Notifications;

use App\Channels\Pinpoint\PinpointSmsMessage;
use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationCompleted extends Notification
{
    use Queueable;

    public $application;

    /**
     * Create a new notification instance.
     *
     * @param  Application  $application
     * @return void
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
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
                    ->subject('Application '.$this->application->control_number)
                    ->line('Majority just approved the application!')
                    ->action('Proceed to ATC Portal', route('applications.show', ['application' => $this->application->id]))
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
            ->create('Majority just approved the '.$this->application->control_number.' application!');
    }
}
