<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMqttDataTable extends Migration
{
    public function up()
    {
        Schema::create('mqtt_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kendaraan_id')->constrained('kendaraan');
            $table->float('longitude');
            $table->float('latitude');
            $table->timestamp('tanggal');
            $table->string('status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mqtt_data');
    }
}
