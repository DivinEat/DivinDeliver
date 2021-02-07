<?php


namespace App\SDK\UberEats;


use App\SDK\BaseOrderSDKInterface;
use App\SDK\BaseSDK;

class OrderSDK extends BaseSDK implements BaseOrderSDKInterface
{
    use UberEatsUrl;

    public function getOrderDetails(string $orderID): ?array
    {
        $response = $this->client->request(
            'GET',
            $this->getUrlWithParams('ordersUrl', ['orderID' => $orderID])
        );

        if ($response->getStatusCode() !== 200)
            return null;

        return $response->toArray();
    }

    public function getActiveCreatedOrders(string $storeID): ?array
    {
        $response = $this->client->request(
            'GET',
            $this->getUrlWithParams('storeActiveOrdersUrl', ['storeID' => $storeID])
        );

        if ($response->getStatusCode() !== 200)
            return null;

        return $response->toArray();
    }

    public function getCancelOrders(string $storeID)
    {
        $response = $this->client->request(
            'GET',
            $this->getUrlWithParams('storeCancelOrdersUrl', ['storeID' => $storeID])
        );

        if ($response->getStatusCode() !== 200)
            return null;

        return $response->toArray();
    }

    public function acceptOrder(string $orderID): bool
    {
        $response = $this->client->request(
            'POST',
            $this->getUrlWithParams('acceptOrderUrl', ['orderID' => $orderID]),
            ['body' =>['reason' => 'accepted']]
        );

        return $response->getStatusCode() === 204;
    }

    public function denyOrder(string $orderID, string $explanation = 'Sorry not sorry'): bool
    {
        $response = $this->client->request(
            'POST',
            $this->getUrlWithParams('denyOrderUrl', ['orderID' => $orderID]),
            ['body' =>['reason' => ['explanation' => $explanation]]]
        );

        return $response->getStatusCode() === 204;
    }

    public function cancelOrder(string $orderID, string $reason = 'KITCHEN_CLOSED'): bool
    {
        $response = $this->client->request(
            'POST',
            $this->getUrlWithParams('cancelOrderUrl', ['orderID' => $orderID]),
            ['body' =>['reason' => $reason]]
        );

        return $response->getStatusCode() === 200;
    }
}