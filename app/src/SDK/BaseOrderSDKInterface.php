<?php


namespace App\SDK;


interface BaseOrderSDKInterface
{
    public function getOrderDetails(string $orderID);

    public function getActiveCreatedOrders(string $storeID);

    public function getCancelOrders(string $storeID);

    public function acceptOrder(string $orderID);

    public function denyOrder(string $orderID);

    public function cancelOrder(string $orderID);
}