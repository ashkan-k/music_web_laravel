<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettingRequest;
use App\Models\Setting;
use Froiden\RestAPI\ApiController;
use Illuminate\Http\Request;

class SettingController extends ApiController
{
    protected $model = Setting::class;
    protected $storeRequest = SettingRequest::class;
    protected $updateRequest = SettingRequest::class;

    public function panel_settings()
    {
        $settings = Setting::InitSettings();
        return response(['data' => $settings]);
    }
}
