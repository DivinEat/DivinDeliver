<?php


namespace App\SDK\Deliveroo;


trait DeliverooUrl
{
    private string $menusUrl = 'http://217.160.64.31/FakeDeliveroo/eats/stores/{storeID}/menus';

    private string $ordersUrl = 'http://217.160.64.31/FakeDeliveroo/eats/orders/{orderID}';

    private string $storeActiveOrdersUrl = 'http://217.160.64.31/FakeDeliveroo/eats/stores/{storeID}/created-orders';

    private string $storeCancelOrdersUrl = 'http://217.160.64.31/FakeDeliveroo/eats/stores/{storeID}/canceled-orders';

    private string $acceptOrderUrl = 'http://217.160.64.31/FakeDeliveroo/eats/orders/{orderID}/accept_pos_order';

    private string $denyOrderUrl = 'http://217.160.64.31/FakeDeliveroo/eats/orders/{orderID}/deny_pos_order';

    private string $cancelOrderUrl = 'http://217.160.64.31/FakeDeliveroo/eats/orders/{orderID}/cancel';

    private string $storeDetailsUrl = 'http://217.160.64.31/FakeDeliveroo/eats/stores/{storeId}';

    private string $allStoreUrl = 'http://217.160.64.31/FakeDeliveroo/eats/stores';

    private string $restaurantStatusUrl = 'http://217.160.64.31/FakeDeliveroo/eats/store/{store_id}/status';

    private string $storeHolidayHoursUrl = 'http://217.160.64.31/FakeDeliveroo/eats/stores/{store_id}/holiday-hours';

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