<?php


namespace App\SDK;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class BaseSDK
{
    protected HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }
}