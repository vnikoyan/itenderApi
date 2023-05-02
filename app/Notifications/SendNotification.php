<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;
use App\Models\User\User;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;
use NotificationChannels\Telegram\TelegramUpdates;

class SendNotification extends Notification
{
    use Queueable;

    public $chat_id = false;
    public $user_id;
    public $message;
    public $url;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user_id, $message, $url)
    {
        $this->user_id = $user_id;
        $this->message = $message;
        $this->url = $url;
        $user = User::find($this->user_id);
        if($user->telegram_id){
            $this->chat_id = $user->telegram_id;
        }
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['telegram'];
    }

    public function toTelegram($notifiable)
    {
        try {
            if($this->chat_id){
                if($this->url){
                    return TelegramMessage::create()
                    ->to($this->chat_id)
                    ->content($this->message)
                    ->button('Տեսնել', $this->url);
                } else {
                    return TelegramMessage::create()
                    ->to($this->chat_id)
                    ->content($this->message);
                }
            }
        } catch (\Throwable $th) {
            Log::channel('test')->info('Error Telegram');
        }
    }
}
