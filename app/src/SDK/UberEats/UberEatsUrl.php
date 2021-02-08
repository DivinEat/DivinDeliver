<?php


namespace App\SDK\UberEats;


trait UberEatsUrl
{
    private string $menusUrl = 'http://93.90.203.240/FakeUberEats/eats/stores/{storeID}/menus';

    private string $ordersUrl = 'http://93.90.203.240/FakeUberEats/eats/order/{orderID}';

    private string $storeActiveOrdersUrl = 'http://93.90.203.240/FakeUberEats/eats/stores/{storeID}/created-orders';

    private string $storeCancelOrdersUrl = 'http://93.90.203.240/FakeUberEats/eats/stores/{storeID}/canceled-orders';

    private string $acceptOrderUrl = 'http://93.90.203.240/FakeUberEats/eats/orders/{orderID}/accept_pos_order';

    private string $denyOrderUrl = 'http://93.90.203.240/FakeUberEats/eats/orders/{orderID}/deny_pos_order';

    private string $cancelOrderUrl = 'http://93.90.203.240/FakeUberEats/eats/orders/{orderID}/cancel';

    private string $storeDetailsUrl = 'https://api.uber.com/v1/eats/stores/{storeId}';

    private string $allStoreUrl = 'http://93.90.203.240/FakeUberEats/eats/stores';

    private string $restaurantStatusUrl = 'http://93.90.203.240/FakeUberEats/eats/store/{store_id}/status';

    private string $storeHolidayHoursUrl = 'http://93.90.203.240/FakeUberEats/eats/stores/{store_id}/holiday-hours';

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