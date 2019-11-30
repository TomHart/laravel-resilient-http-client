<?php


namespace TomHart\HttpClient;


use GuzzleHttp\ClientInterface;
use Illuminate\Support\ServiceProvider;

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
        $this->mergeConfigFrom(__DIR__.'/../config/resilient-http.php', 'resilient-http');

        $this->app->bind(ClientInterface::class, ResilientClient::class);
    }
}
