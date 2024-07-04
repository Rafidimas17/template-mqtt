<?php

// app/Http/Controllers/MqttController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MqttData;
use App\Events\MqttDataReceived;

class MqttController extends Controller
{
    public function store(Request $request)
    {
        $data = MqttData::create($request->all());
        broadcast(new MqttDataReceived($data)); // Menggunakan broadcast
        return response()->json($data);
    }
}
