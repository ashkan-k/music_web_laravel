<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LikeDislike;
use Froiden\RestAPI\ApiController;
use Illuminate\Http\Request;

class LikeDisLikeController extends ApiController
{
    protected $model = LikeDislike::class;
}
