<?php


interface Client
{


    /**
     * Perform a GET request against a URL.
     * @param string $uri
     * @param array $headers
     * @return string
     */
    public function get(string $uri, array $headers = []): string;
}
