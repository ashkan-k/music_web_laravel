<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\WishListRequest;
use App\Models\WishList;
use Froiden\RestAPI\ApiController;
use Illuminate\Http\Request;

class WishListController extends ApiController
{
    protected $model = WishList::class;
    protected $storeRequest = WishListRequest::class;
    protected $updateRequest = WishListRequest::class;
}
