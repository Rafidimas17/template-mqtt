<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\MqttData;
use Illuminate\Http\Request;
use App\Events\MqttDataUpdated;
use App\Events\KendaraanStatusUpdated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Broadcast;

class MqttController extends Controller
{
    public function index()
    {
        $kendaraan = Kendaraan::all();
        return view('welcome', compact('kendaraan'));
    }

    public function updateStatus(Request $request, $id)
{
    $validated = $request->validate([
        'status' => 'required|in:aktif,nonaktif',
    ]);

    // Update status in kendaraan table
    $kendaraan = Kendaraan::find($id);
    if (!$kendaraan) {
        return response()->json(['status' => 'error', 'message' => 'Kendaraan not found'], 404);
    }

    $kendaraan->status = $validated['status'];
    $kendaraan->save();

    // Broadcast status change
    event(new KendaraanStatusUpdated($kendaraan));

    return response()->json(['status' => 'success']);
}


    public function storeMqttData(Request $request)
    {
        // Validate incoming data
        $data = $request->validate([
            'kendaraan_id' => 'required|exists:kendaraan,id',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'tanggal' => 'required|date',
            'status' => 'nullable|string|in:aktif,nonaktif', // Optional status field
        ]);
    
        // Insert data into mqtt_data table
        $mqttData = MqttData::create([
            'kendaraan_id' => $data['kendaraan_id'],
            'longitude' => $data['longitude'],
            'latitude' => $data['latitude'],
            'tanggal' => $data['tanggal'],
            'status' => $data['status'] ?? 'default_value', // Ensure status is provided or use a default value
        ]);
    
        // Update the kendaraan table with new status if provided
        if (isset($data['status'])) {
            DB::table('kendaraan')
                ->where('id', $data['kendaraan_id'])
                ->update(['status' => $data['status']]);
        }
    
        // Broadcast new MQTT data
        event(new MqttDataUpdated($mqttData));
    
        // event(new MqttDataUpdated($mqttData));

        // Return the stored data for confirmation
        return response()->json([
            'status' => 'success',
            'data' => $mqttData
        ]);
    }
    
}
