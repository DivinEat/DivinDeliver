<?php


namespace App\SDK;


interface BaseOrderSDKInterface
{
    public function getOrderDetails(string $id);

    public function getActiveCreatedOrders();

    public function acceptOrder(string $id);

    public function denyOrder(string $id);

    public function cancelOrder(string $id);
}