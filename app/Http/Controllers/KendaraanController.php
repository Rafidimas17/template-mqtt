<?php
// app/Http/Controllers/KendaraanController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kendaraan;

class KendaraanController extends Controller
{
    public function updateStatus(Request $request)
    {
        $request->validate([
            'kendaraan_id' => 'required|exists:kendaraan,id',
            'status' => 'required|in:0,1',
        ]);

        $kendaraan = Kendaraan::find($request->kendaraan_id);

        if (!$kendaraan) {
            return response()->json(['error' => 'Kendaraan not found'], 404);
        }

        $kendaraan->status = $request->status === '1' ? 'aktif' : 'tidak aktif';
        $kendaraan->save();

        return response()->json($kendaraan);
    }
    public function getLatestData(Request $request)
{
    $kendaraan_id = $request->input('kendaraan_id');

    // Assuming you have models Kendaraan and MqttData
    $kendaraan = Kendaraan::find($kendaraan_id);
    $latestMqttData = MqttData::where('kendaraan_id', $kendaraan_id)->latest()->first();

    if ($kendaraan && $latestMqttData) {
        $data = [
            'kendaraan_id' => $kendaraan->id,
            'nama' => $kendaraan->nama,
            'longitude' => $latestMqttData->longitude,
            'latitude' => $latestMqttData->latitude,
            'status' => $kendaraan->status,
        ];
        return response()->json($data);
    }

    return response()->json(['error' => 'Data not found'], 404);
}
    public function status(){
        $data=Kendaraan::all();
        return response()->json($data);
    }
}
