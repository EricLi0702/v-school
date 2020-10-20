<?php

namespace App\Events;

use App\VideoUpload;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewVideoLectureUpload implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $uploadVideo;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($uploadVideo)
    {
        $this->uploadVideo = $uploadVideo;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('uploadVideoCh');
    }
}
