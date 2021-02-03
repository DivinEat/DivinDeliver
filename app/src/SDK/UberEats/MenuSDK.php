<?php


namespace App\SDK\UberEats;


use App\SDK\BaseMenuSDKInterface;
use App\SDK\BaseSDK;

class MenuSDK extends BaseSDK implements BaseMenuSDKInterface
{
    public function getMenus(): ?array
    {
        $response = $this->client->request(
            'GET',
            'https://api.uber.com/v2/eats/stores/{store_id}/menus'
        );

        return $response->getStatusCode() !== 200 ? null : $response->toArray();
    }

    public function uploadMenu(): void
    {
        $response = $this->client->request(
            'PUT',
            'https://api.uber.com/v2/eats/stores/{store_id}/menus'
        );
    }

    public function updateMenu()
    {
        // TODO: Implement updateMenu() method.
    }
}