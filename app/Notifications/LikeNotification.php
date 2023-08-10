<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class LikeNotification extends Notification implements ShouldBroadcast
{
    use Queueable;
    public $user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['broadcast','database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            "message" => [
            'id' => $this->id,
            'type' => get_class($this),
            'notifiable_type' => $notifiable->getMorphClass(),
            'notifiable_id' => $notifiable->getKey(),
            'data' => [
                'name' =>$this->user->name,
                'image' =>$this->user->profile_image,
                'message'=>$this->user->name.' has likes your Post  ',
            ],
            'read_at' => null,
            'created_at' => now()->format('Y-m-d H:i:s'), 
            'updated_at' => now()->format('Y-m-d H:i:s'), 
            ]
        ]);
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
            'name' =>$this->user->name,
            'image' =>$this->user->profile_image,
            'message'=>$this->user->name.' has likes your Post  ',
        ];
    }
}
