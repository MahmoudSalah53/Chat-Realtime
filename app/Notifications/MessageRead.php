<?php

namespace App\Notifications;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MessageRead extends Notification implements ShouldBroadcast
{
    use Queueable;

    public $conversation_id;
    public $receiverId;

    public function __construct($conversation_id, $receiverId)
    {
        $this->conversation_id = $conversation_id;
        $this->receiverId = $receiverId; // إضافة هذا المتغير
    }

    public function via(object $notifiable): array
    {
        return ['broadcast'];
    }

    public function broadcastOn()
    {
        return new PrivateChannel('users.' . $this->receiverId);
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'conversation_id' => $this->conversation_id,
            'type' => 'App\\Notifications\\MessageRead', // إضافة النوع
        ]);
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'conversation_id' => $this->conversation_id,
        ];
    }
}