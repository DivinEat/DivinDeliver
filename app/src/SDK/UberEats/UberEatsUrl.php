<?php


namespace App\SDK\UberEats;


trait UberEatsUrl
{
    private string $menusUrl = 'http://host.docker.internal:8000/eats/stores/{storeID}/menus';

    private string $ordersUrl = 'http://host.docker.internal:8000/eats/order/{orderID}';

    private string $storeActiveOrdersUrl = 'http://host.docker.internal:8000/eats/stores/{storeID}/created-orders';

    private string $storeCancelOrdersUrl = 'http://host.docker.internal:8000/eats/stores/{storeID}/canceled-orders';

    private string $acceptOrderUrl = 'http://host.docker.internal:8000/eats/orders/{orderID}/accept_pos_order';

    private string $denyOrderUrl = 'http://host.docker.internal:8000/eats/orders/{orderID}/deny_pos_order';

    private string $cancelOrderUrl = 'http://host.docker.internal:8000/eats/orders/{orderID}/cancel';

    private string $storeDetailsUrl = 'https://api.uber.com/v1/eats/stores/{storeId}';

    private string $allStoreUrl = 'http://host.docker.internal:8000/eats/stores';

    private string $restaurantStatusUrl = 'http://host.docker.internal:8000/eats/store/{store_id}/status';

    private string $storeHolidayHoursUrl = 'http://host.docker.internal:8000/eats/stores/{store_id}/holiday-hours';

    protected function getUrlWithParams(string $urlName, array $params =  []): ?string
    {
        if (! isset($this->$urlName))
            return null;

        $strToReplace = $this->$urlName;
        foreach ($params as $key => $value)
            $strToReplace = str_replace('{' . $key . '}', urlencode($value), $strToReplace);

        return $strToReplace;
    }
}