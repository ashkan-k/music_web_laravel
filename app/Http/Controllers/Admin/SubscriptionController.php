<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionRequest;
use App\Models\Subscription;
use Froiden\RestAPI\ApiController;
use Illuminate\Http\Request;

class SubscriptionController extends ApiController
{
    protected $model = Subscription::class;
    protected $storeRequest = SubscriptionRequest::class;
    protected $updateRequest = SubscriptionRequest::class;
}
