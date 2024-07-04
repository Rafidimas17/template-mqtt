<?php

// app/Events/MqttDataReceived.php

// app/Events/MqttDataReceived.php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\MqttData;

class MqttDataReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $mqttData;

    public function __construct(MqttData $mqttData)
    {
        $this->mqttData = $mqttData;
    }

    public function broadcastOn()
    {
        return new Channel('mqtt-data');
    }
}
