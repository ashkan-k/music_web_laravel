<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Responses;
use App\Models\Album;
use App\Models\Singer;
use App\Models\Subscriber;
use App\Models\Track;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    use Responses;

    public function statistics_info()
    {
        $response = [
            'users_count' => User::count(),
            'albums_count' => Album::count(),
            'tracks_count' => Track::count(),
            'singers_count' => Singer::count(),
            'subscribers_count' => Subscriber::count(),
        ];

        return $this->SuccessResponse($response);
    }
}
