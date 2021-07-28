<?php


namespace App\SDK\UberEats;


use App\SDK\BaseMenuSDKInterface;
use App\SDK\BaseSDK;

class MenuSDK extends BaseSDK implements BaseMenuSDKInterface
{
    use UberEatsUrl;

    public function getMenus(string $storeID): ?array
    {
        $response = $this->client->request(
            'GET',
             $this->getUrlWithParams('menusUrl', ['storeID' => $storeID])
        );

        return $response->getStatusCode() !== 200 ? null : $response->toArray();
    }

    public function uploadMenu(string $storeID, array $params): void
    {
        $response = $this->client->request(
            'PUT',
            $this->getUrlWithParams('menusUrl', ['storeID' => $storeID]),
            ['body' => $params]
        );
    }
}