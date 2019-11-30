<?php

namespace TomHart\HttpClient;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Psr\Http\Message\ResponseInterface;

class ResilientClient extends Client implements ClientInterface
{


    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return mixed|ResponseInterface
     * @throws GuzzleException
     */
    public function request($method, $uri = '', array $options = [])
    {

        $prefix = config('resilient-http.cache_prefix');
        $key = sprintf('%s %s %s', $prefix , $method, $uri);

        try {
            $response = parent::request($method, $uri, $options);
            $ttl = config('resilient-http.cache_time');
            Cache::put($key, $response, $ttl);

            return $response;
        } catch (GuzzleException $ex) {

            $uncached = Cache::get($key);

            if ($uncached) {
                return $uncached;
            }

            throw $ex;
        }
    }

}
