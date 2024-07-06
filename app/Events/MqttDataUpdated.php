<?php
namespace App\Events;

use App\Models\MqttData;
use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MqttDataUpdated
{
    use Dispatchable, SerializesModels;

    public $mqttData;

    public function __construct(MqttData $mqttData)
    {
        $this->mqttData = $mqttData;
    }

    public function broadcastOn()
    {
        return new Channel('mqtt_data.' . $this->mqttData->kendaraan_id);
    }
}
