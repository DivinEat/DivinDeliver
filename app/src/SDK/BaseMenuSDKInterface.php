<?php

namespace App\SDK;

interface BaseMenuSDKInterface
{
    public function getMenus(string $storeID): ?array;

    public function uploadMenu(string $storeID, array $params);

//    public function updateMenu();
}