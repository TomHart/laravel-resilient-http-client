# Laravel Resilient HTTP Client

This library allows you to make HTTP requests with built in cache resiliency.
The client simply wraps around [Guzzle's](http://docs.guzzlephp.org/en/stable/index.html) `request` method, so it 
you already use that, this will be very easy to implement into your app.

### Usage 

```
use TomHart\HttpClient\Contracts\ResilientClientInterface;

$client = app(ResilientClientInterface::class);

$response = $client->request('GET', '/url');
```

You can publish the config-file with:

`php artisan vendor:publish --provider="TomHart\HttpClient\ResilientServiceProvider" --tag="config"`

### Caching
This library utilises 2 different caches, one short term cache, and one longer term cache.
The short term cache is used every time you call `request`. If the short term cache is a miss, and the HTTP request
fails, it will only then look into the long term cache.

### Config Options

| Config Item           | Usage                                              | Default  |
| --------------------- | -------------------------------------------------- | --------:|
| `cache_time`          | What TTL (seconds) to use for the short term cache | 60       |
| `fallback_cache_time` | What TTL (seconds) to use for the long term cache  | 3600     |
