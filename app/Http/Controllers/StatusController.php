<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kendaraan;
use App\Events\MqttDataUpdated;

class StatusController extends Controller
{
    /**
     * Update the status of the vehicle and broadcast the change.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        // Validate incoming request
        $validated = $request->validate([
            'status' => 'required|string',
            'kendaraan_id' => 'required|integer',
        ]);

        // Find the kendaraan by ID
        $kendaraan = Kendaraan::find($validated['kendaraan_id']);

        if ($kendaraan) {
            // Update the kendaraan status
            $kendaraan->status = $validated['status'] === '1' ? 'aktif' : 'tidak aktif';
            $kendaraan->save();

            // Broadcast the updated status
            broadcast(new MqttDataUpdated([
                'status' => $kendaraan->status,
                'kendaraan_id' => $kendaraan->id,
                'nama' => $kendaraan->nama,
                'longitude' => $kendaraan->longitude,
                'latitude' => $kendaraan->latitude,
            ]));

            return response()->json($kendaraan);
        }

        return response()->json(['error' => 'Kendaraan not found'], 404);
    }

    /**
     * Broadcast the status update event.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function broadcastUpdate(Request $request)
    {
        // Validate incoming request
        $validated = $request->validate([
            'status' => 'required|string',
            'kendaraan_id' => 'required|integer',
        ]);

        // Find the kendaraan by ID
        $kendaraan = Kendaraan::find($validated['kendaraan_id']);

        if ($kendaraan) {
            // Update the kendaraan status
            $kendaraan->status = $validated['status'] === '1' ? 'aktif' : 'tidak aktif';
            $kendaraan->save();

            // Broadcast the updated status
            broadcast(new MqttDataUpdated([
                'status' => $kendaraan->status,
                'kendaraan_id' => $kendaraan->id,
                'nama' => $kendaraan->nama,
                'longitude' => $kendaraan->longitude,
                'latitude' => $kendaraan->latitude,
            ]));

            return response()->json(['message' => 'Update broadcasted']);
        }

        return response()->json(['error' => 'Kendaraan not found'], 404);
    }
}
