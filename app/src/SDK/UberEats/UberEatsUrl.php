<?php


namespace App\SDK\UberEats;


trait UberEatsUrl
{
    private string $menusUrl = 'http://app/eats/stores/{storeID}/menus';

    private string $ordersUrl = 'http://app/eats/orders/{orderID}';

    private string $storeActiveOrdersUrl = 'http://app/eats/stores/{storeID}/created-orders';

    private string $storeCancelOrdersUrl = 'http://app/eats/stores/{storeID}/canceled-orders';

    private string $acceptOrderUrl = 'http://app/eats/orders/{orderID}/accept_pos_order';

    private string $denyOrderUrl = 'http://app/eats/orders/{orderID}/deny_pos_order';

    private string $cancelOrderUrl = 'http://app/eats/orders/{orderID}/cancel';

    private string $storeDetailsUrl = 'http://app/eats/stores/{storeId}';

    private string $allStoreUrl = 'http://app/eats/stores';

    private string $restaurantStatusUrl = 'http://app/eats/store/{store_id}/status';

    private string $storeHolidayHoursUrl = 'http://app/eats/stores/{store_id}/holiday-hours';

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
