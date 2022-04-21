<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SingerRequest;
use App\Models\Singer;
use Froiden\RestAPI\ApiController;
use Illuminate\Http\Request;

class SingerController extends ApiController
{
    protected $model = Singer::class;
    protected $storeRequest = SingerRequest::class;
    protected $updateRequest = SingerRequest::class;
}
