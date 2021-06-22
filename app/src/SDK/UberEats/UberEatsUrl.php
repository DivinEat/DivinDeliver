<?php


namespace App\SDK\UberEats;


trait UberEatsUrl
{
    private string $menusUrl = 'http://localhost:8000/eats/stores/{storeID}/menus';

    private string $ordersUrl = 'http://localhost:8000/eats/orders/{orderID}';

    private string $storeActiveOrdersUrl = 'http://localhost:8000/eats/stores/{storeID}/created-orders';

    private string $storeCancelOrdersUrl = 'http://localhost:8000/eats/stores/{storeID}/canceled-orders';

    private string $acceptOrderUrl = 'http://localhost:8000/eats/orders/{orderID}/accept_pos_order';

    private string $denyOrderUrl = 'http://localhost:8000/eats/orders/{orderID}/deny_pos_order';

    private string $cancelOrderUrl = 'http://localhost:8000/eats/orders/{orderID}/cancel';

    private string $storeDetailsUrl = 'http://localhost:8000/eats/stores/{storeId}';

    private string $allStoreUrl = 'http://localhost:8000/eats/stores';

    private string $restaurantStatusUrl = 'http://localhost:8000/eats/store/{store_id}/status';

    private string $storeHolidayHoursUrl = 'http://localhost:8000/eats/stores/{store_id}/holiday-hours';

    protected function getUrlWithParams(string $urlName, array $params =  []): ?string
    {
        if (!isset($this->$urlName))
            return null;

        $strToReplace = $this->$urlName;
        foreach ($params as $key => $value)
            $strToReplace = str_replace('{' . $key . '}', urlencode($value), $strToReplace);

        return $strToReplace;
    }
}
