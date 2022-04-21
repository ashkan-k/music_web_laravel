<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenreRequest;
use App\Models\Genre;
use Froiden\RestAPI\ApiController;
use Illuminate\Http\Request;

class GenreController extends ApiController
{
    protected $model = Genre::class;
    protected $storeRequest = GenreRequest::class;
    protected $updateRequest = GenreRequest::class;
}
