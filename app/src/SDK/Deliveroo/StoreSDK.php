<?php


namespace App\SDK\Deliveroo;


use App\SDK\BaseSDK;
use App\SDK\BaseStoreSDKInterface;

class StoreSDK extends BaseSDK implements BaseStoreSDKInterface
{
    use DeliverooUrl;

    public function getStores()
    {
        $response = $this->client->request(
            'GET',
            $this->getUrlWithParams('allStoresUrl')
        );

        return $response->getStatusCode() !== 200 ? null : $response->toArray();
    }

    public function getStore(string $storeID)
    {
        $response = $this->client->request(
            'GET',
            $this->getUrlWithParams('storeDetails', ['storeID' => $storeID])
        );

        return $response->getStatusCode() !== 200 ? null : $response->toArray();
    }

    public function getStatus(string $storeID)
    {
        $response = $this->client->request(
            'GET',
            $this->getUrlWithParams('restaurantStatusUrl', ['storeID' => $storeID])
        );

        return $response->getStatusCode() !== 200 ? null : $response->toArray();
    }

    public function setStatus(string $storeID, string $status)
    {
        $response = $this->client->request(
            'POST',
            $this->getUrlWithParams('restaurantStatusUrl', ['storeID' => $storeID]),
            ['body' => ['status' => $status]]
        );

        return $response->getStatusCode() !== 204 ? null : $response->toArray();
    }

    public function getHolidayHours(string $storeID)
    {
        $response = $this->client->request(
            'GET',
            $this->getUrlWithParams('storeHolidayHoursUrl', ['storeID' => $storeID])
        );

        return $response->getStatusCode() !== 200 ? null : $response->toArray();
    }

    public function setHolidayHours(string $storeID, array $holidayHours)
    {
        $response = $this->client->request(
            'POST',
            $this->getUrlWithParams('storeHolidayHoursUrl', ['storeID' => $storeID]),
            ['body' => ['holiday_hours' => $holidayHours]]
        );

        return $response->getStatusCode() !== 200 ? null : $response->toArray();
    }
}