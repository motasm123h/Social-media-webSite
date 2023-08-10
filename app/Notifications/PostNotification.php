<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostNotification extends Notification
{
    use Queueable;

    public $user;
    public $post_id;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user,$post_id)
    {
        $this->user = $user;
        $this->post_id = $post_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
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
            'post_id'=>$this->post_id,
            'user_id'=>$this->user->id,
            'name' =>$this->user->name,
            'email'=>$this->user->email,
        ];
    }
}
