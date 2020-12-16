<?php

namespace App\SDK;

interface BaseMenuSDKInterface
{
    public function getMenus();

    public function uploadMenu();

    public function updateMenu();
}