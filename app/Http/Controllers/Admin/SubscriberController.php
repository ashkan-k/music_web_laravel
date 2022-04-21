<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriberRequest;
use App\Models\Subscriber;
use Froiden\RestAPI\ApiController;
use Illuminate\Http\Request;

class SubscriberController extends ApiController
{
    protected $model = Subscriber::class;
    protected $storeRequest = SubscriberRequest::class;
    protected $updateRequest = SubscriberRequest::class;
}
