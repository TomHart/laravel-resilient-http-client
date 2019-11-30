<?php


namespace TomHart\HttpClient;


use Illuminate\Support\ServiceProvider;
use TomHart\HttpClient\Contracts\ResilientClientInterface;

class ResilientServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/resilient-http.php' => config_path('resilient-http.php'),
        ], 'config');
    }


    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/resilient-http.php', 'resilient-http');

        $this->app->bind(ResilientClientInterface::class, ResilientClient::class);
    }
}
