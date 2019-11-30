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
     * Gets the key to store the response in.
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return string
     */
    private function getCacheKey(string $method, string $uri, array $options = []): string
    {
        return "$method.$uri";
    }

    /**
     * What key should we look for in the fallback back.
     * @param string $key
     * @return string
     */
    private function getFallbackCacheKey(string $key): string
    {
        return 'fallback_' . $key;
    }

    /**
     * Requests a URI, and if it fails, looks in the cache for a previous call.
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function request($method, $uri = '', array $options = []): ResponseInterface
    {

        // Build the key to look up in the cache.
        $key = $this->getCacheKey($method, $uri, $options);

        // If we have a response, just return it now.
        if (Cache::has($key)) {
            return Cache::get($key);
        }

        try {

            // Not in the cache, so make the request.
            $response = parent::request($method, $uri, $options);

            // Put it in the usual cache.
            Cache::put($key, $response, config('resilient-http.cache_time'));

            // Put it in the fallback cache.
            Cache::put($this->getFallbackCacheKey($key), $response, config('resilient-http.fallback_cache_time'));

            return $response;
        } catch (GuzzleException $ex) {

            // If there's an entry in the fallback cache, return that.
            if (Cache::has($fallbackKey = $this->getFallbackCacheKey($key))) {
                return Cache::get($fallbackKey);
            }

            throw $ex;
        }
    }

}
