<?php

namespace App\Events\Models\Comment;

use App\Models\Comment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class CommentCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected Comment $comment;
    /**
     * Create a new event instance.
     * 
     * @param Post $comment - comment object for update
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
