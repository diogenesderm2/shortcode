<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReportProcessed implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public function broadcastOn(): Channel
    {
        return new Channel('reports');
    }

    public function broadcastAs(): string
    {
        return 'ReportProcessed';
    }

    public function broadcastWith(): array
    {
        return [
            'status' => 'done',
        ];
    }
}