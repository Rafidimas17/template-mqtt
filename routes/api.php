<?php

use App\Http\Controllers\MqttController;

Route::post('/control', [MqttController::class, 'control']);

