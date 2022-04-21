<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Track;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        if(request()->has('menu')){
            return redirect('/admin');
        }

        $tracks = Track::all();
        return view('front.index', compact('tracks'));
    }
}
