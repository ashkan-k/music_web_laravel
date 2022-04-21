<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Responses;
use App\Http\Traits\Uploader;
use App\Models\Comment;
use Froiden\RestAPI\ApiController;
use Illuminate\Http\Request;

class CommentController extends ApiController
{
    protected $model = Comment::class;
}
