<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Comment implements shouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;  
    public $post;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($comment ,$post)
    {
        $this->comment=$comment;
        $this->post=$post;
    }

    public function broadcastWith(){
        return [
            'comment' =>$this->comment
        ];
    }
    

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('HashTag.'.$this->post->hashtag_id.'post.'.$this->post->id.'.user.'.$this->comment->user->id);
    }
}
