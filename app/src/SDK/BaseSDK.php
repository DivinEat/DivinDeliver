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

    protected function setTypeToOrders(array $orders, string $type): array
    {
        return array_map(function ($order) use ($type) {
            $order['deliver'] = $type;

            return $order;
        }, $orders);
    }
}
