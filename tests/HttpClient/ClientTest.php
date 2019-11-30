<?php

namespace TomHart\HttpClient\Tests\HttpClient;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Cache;
use TomHart\HttpClient\ResilientClient;
use TomHart\HttpClient\Tests\TestCase;

class ClientTest extends TestCase
{

    /**
     * When making the API call, if a subsequent one fails, make sure it returns the previous request.
     * @throws GuzzleException
     */
    public function test_previous_response_cached()
    {
        $mock = new MockHandler([
            new Response(200),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ]);

        $handler = HandlerStack::create($mock);

        /** @var ClientInterface $client */
        $client = new ResilientClient(['handler' => $handler]);

        $code = $client->request('GET', '/')->getStatusCode();
        $this->assertSame(200, $code);

        $code = $client->request('GET', '/')->getStatusCode();
        $this->assertSame(200, $code);
    }

    /**
     * When making the API call, if a subsequent one fails, make sure it returns the previous request from the fallback.
     * @throws GuzzleException
     */
    public function test_previous_response_returned_from_fallback_cache()
    {
        $mock = new MockHandler([
            new Response(200),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ]);

        $handler = HandlerStack::create($mock);

        /** @var ClientInterface $client */
        $client = new ResilientClient(['handler' => $handler]);

        $code = $client->request('GET', '/')->getStatusCode();
        $this->assertSame(200, $code);

        Cache::forget('GET./');

        $code = $client->request('GET', '/')->getStatusCode();
        $this->assertSame(200, $code);
    }

    /**
     * If the request fails, and there isn't anything in the cache to return, make sure the
     * error bubbles up,
     * @throws GuzzleException
     */
    public function test_exception_bubbles_if_cache_miss()
    {
        $this->expectException(GuzzleException::class);
        $mock = new MockHandler([
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ]);

        $handler = HandlerStack::create($mock);

        /** @var ClientInterface $client */
        $client = new ResilientClient(['handler' => $handler]);

        $client->request('GET', '/');
    }
}
