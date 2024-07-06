<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('kendaraan.{id}', function ($user, $id) {
    return true;
});

Broadcast::channel('mqtt_data.{kendaraan_id}', function ($user, $kendaraan_id) {
    return true;
});

Broadcast::channel('kendaraan-channel', function ($user) {
    return true; // Pastikan Anda menyesuaikan otentikasi jika diperlukan
});