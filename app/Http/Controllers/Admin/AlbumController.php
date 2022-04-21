<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AlbumRequest;
use App\Models\Album;
use App\Models\Singer;
use Froiden\RestAPI\ApiController;

class AlbumController extends ApiController
{
    protected $model = Album::class;
    protected $storeRequest = AlbumRequest::class;
    protected $updateRequest = AlbumRequest::class;

    public function singer_albums($id)
    {
        $singer = Singer::findOrFail($id);
        return ['data' => $singer->albums];
    }
}
