<?php

// database/migrations/create_mqtt_data_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMqttDataTable extends Migration
{
    public function up()
    {
        Schema::create('mqtt_data', function (Blueprint $table) {
            $table->id();
            $table->string('idkendaraan');
            $table->decimal('longitude', 10, 7);
            $table->decimal('latitude', 10, 7);
            $table->timestamp('tanggal');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mqtt_data');
    }
}

