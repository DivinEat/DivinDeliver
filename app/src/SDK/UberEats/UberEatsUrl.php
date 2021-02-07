<?php


namespace App\SDK\UberEats;


trait UberEatsUrl
{
    private string $menusUrl = 'https://api.uber.com/v2/eats/stores/{storeID}/menus';

    private string $ordersUrl = 'https://api.uber.com/v2/eats/order/{orderID}';

    private string $storeActiveOrdersUrl = 'https://api.uber.com/v1/eats/stores/{storeID}/created-orders';

    private string $storeCancelOrdersUrl = 'https://api.uber.com/v1/eats/stores/{storeID}/canceled-orders';

    private string $acceptOrderUrl = 'https://api.uber.com/v1/eats/orders/{orderID}/accept_pos_order';

    private string $denyOrderUrl = 'https://api.uber.com/v1/eats/orders/{orderID}/deny_pos_order';

    private string $cancelOrderUrl = 'https://api.uber.com/v1/eats/orders/{orderID}/cancel';

    protected function getUrlWithParams(string $urlName, array $params): ?string
    {
        if (! isset($this->$urlName))
            return null;

        $strToReplace = $this->$urlName;
        foreach ($params as $key => $value)
            $strToReplace = str_replace($strToReplace, '{' . $key . '}', $value);

        return $strToReplace;
    }
}