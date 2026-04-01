<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; // <-- Ye change hua hai
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

// Class ab ShouldBroadcastNow implement kar rahi hai
class AiMessageEvent implements ShouldBroadcastNow 
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $sessionId;

    public function __construct($message, $sessionId)
    {
        $this->message = $message;
        $this->sessionId = $sessionId;
    }

    public function broadcastOn()
    {
        return new Channel('chat.' . $this->sessionId);
    }

    public function broadcastAs()
    {
        return 'ai.replied';
    }
}