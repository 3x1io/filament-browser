<?php

namespace io3x1\FilamentBrowser\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BrowserFileSaved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public $filename
    ) {}

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
