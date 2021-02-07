<?php


namespace App\SDK;


interface BaseStoreSDKInterface
{
    public function getStores();

    public function getStore(string $storeID);

    public function getStatus(string $storeID);

    public function setStatus(string $storeID, string $status);

    public function getHolidayHours(string $storeID);

    public function setHolidayHours(string $storeID, array $holidayHours);
}