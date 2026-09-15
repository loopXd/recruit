<?php


namespace App\Http\Controllers\App\Settings;


use App\Http\Controllers\Controller;
use App\Services\Settings\SettingService;

class JobPointDeliveryController extends Controller
{
    public function __construct(SettingService $service)
    {
        $this->service = $service;
    }

    public function isExists()
    {
        return count($this->service->getCachedMailSettings()) ? 1 : 0;
    }
}