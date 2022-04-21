<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TrackRequest;
use App\Models\Track;
use Froiden\RestAPI\ApiController;
use Illuminate\Http\Request;

class TrackController extends ApiController
{
    protected $model = Track::class;
    protected $storeRequest = TrackRequest::class;
    protected $updateRequest = TrackRequest::class;
}
