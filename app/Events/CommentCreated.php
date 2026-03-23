<?php

namespace App\Events;

use App\Models\Post;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Post $post,
        public int $commentsCount
    ) {}

    public function broadcastOn(): array
    {
        return [new Channel('posts')];
    }

    public function broadcastAs(): string
    {
        return 'comment.created';
    }
}
