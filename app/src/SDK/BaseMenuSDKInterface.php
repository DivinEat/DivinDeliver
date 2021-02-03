<?php

namespace App\SDK;

interface BaseMenuSDKInterface
{
    public function getMenus(): ?array;

    public function uploadMenu();

    public function updateMenu();
}