<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Crypt;

class Verification extends Notification
{
    use Queueable;

    protected $tester;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($tester)
    {
        $this->tester = $tester;
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
        function base64url_encode($data) {
            return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
        }

        return (new MailMessage)
                    ->subject(__('Verify your application!'))
                    ->greeting(__('Hello!'))
                    ->line(__('Hey :first :last, to complete your application you need to verify it, click the button below to continue:', ['first' => $this->tester->first_name, 'last' => $this->tester->last_name]))
                    ->action(__('Verify application'), route('verify', ['id' => base64url_encode($this->tester->id), 'verification' => base64url_encode(Crypt::encryptString('0'.$this->tester->id.$this->tester->first_name.$this->tester->last_name))]))
                    ->line(__('Thank you for sending your application!'));
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
