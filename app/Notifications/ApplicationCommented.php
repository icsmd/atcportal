<?php

namespace App\Notifications;

use App\Channels\Pinpoint\PinpointSmsMessage;
use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationCommented extends Notification
{
    use Queueable;

    public $comment;

    /**
     * Create a new notification instance.
     *
     * @param  Comment  $comment
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
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
                    ->subject('Application '.$this->comment->application->control_number)
                    ->line($this->comment->user->name.' commented on application.')
                    ->action('Proceed to ATC Portal', route('applications.show', ['application' => $this->comment->application->id]))
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
            ->create($this->comment->user->name.' commented on '.$this->comment->application->control_number.'.');
    }
}
