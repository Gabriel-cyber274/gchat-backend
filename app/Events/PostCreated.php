<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostCreated implements shouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    //  /**
    //  * User that sent the message
    //  *
    //  * @var User
    //  */

    public $user;

    // /**
    //  * Message details
    //  *
    //  * @var Post
    //  */
    public $post;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, $post)
    {
        //
        $this->user = $user;
        $this->post = $post;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('post');
    }

    
    public function broadcastWith()
    {
        return ["text" => $this->post];
    }

    public function broadcastAs()
    {
        return 'message';
    }
}

