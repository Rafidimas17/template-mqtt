<?php

namespace App\Events;

use App\Models\Kendaraan;
use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class KendaraanStatusUpdated
{
    use Dispatchable, SerializesModels;

    public $kendaraan;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Kendaraan  $kendaraan
     * @return void
     */
    public function __construct(Kendaraan $kendaraan)
    {
        $this->kendaraan = $kendaraan;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('kendaraan-channel');
    }
}
