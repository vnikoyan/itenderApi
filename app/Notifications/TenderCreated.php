<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;

class TenderCreated extends Notification
{
    use Queueable;

    public $type;
    public $subject;
    public $customer;
    public $tender_id;
    

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->type = $data['type'];
        $this->subject = $data['subject'];
        $this->customer = $data['customer'];
        $this->tender_id = $data['tender_id'];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    // public function toMail($notifiable)
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'type' => $this->type,
            'subject' => $this->subject,
            'customer' => $this->customer,
            'tender_id' => $this->tender_id
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
    */

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'type' => $this->type,
            'subject' => $this->subject,
            'customer' => $this->customer,
            'tender_id' => $this->tender_id
        ]);
    }
}
