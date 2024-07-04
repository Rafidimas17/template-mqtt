<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Bluerhinos\phpMQTT;

class MqttServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('mqtt', function ($app) {
            $host = env('MQTT_HOST', '202.157.186.97');
            $port = env('MQTT_PORT', 1883);
            $username = env('MQTT_USERNAME', 'pablo');
            $password = env('MQTT_PASSWORD', 'costa');

            return new phpMQTT($host, $port, 'LaravelClient');
        });
    }
}
