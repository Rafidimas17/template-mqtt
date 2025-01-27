<?php
// app/Events/MqttDataReceived.php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MqttDataReceived
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $mqttData;

    public function __construct($mqttData)
    {
        $this->mqttData = $mqttData;
    }

    public function broadcastOn()
    {
        return new Channel('mqtt-data');
    }

}

