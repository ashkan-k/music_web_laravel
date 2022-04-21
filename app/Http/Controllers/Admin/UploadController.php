<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadRequest;
use App\Models\Upload;
use Froiden\RestAPI\ApiController;
use Illuminate\Http\Request;

class UploadController extends ApiController
{
    protected $model = Upload::class;
    protected $storeRequest = UploadRequest::class;
    protected $updateRequest = UploadRequest::class;
}
