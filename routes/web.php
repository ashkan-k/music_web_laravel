<?php

use App\Http\Controllers\Front\IndexController;
use App\Http\Controllers\GoogleAuthController;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//
////    dd(\Illuminate\Support\Facades\DB::select('describe singers'));
//
////    User::truncate();
////    $user = User::create(
////        [
////            'first_name' => 'اشکان',
////            'last_name' => 'کریمی',
////            'email' => 'as@gmail.com',
////            'phone' => '09396988720',
////            'password' => Hash::make('123'),
////            'email_verified_at' => Carbon::now(),
////            'is_superuser' => true,
////        ]
////    );
////    auth('api')->login($user);
//
//    return view('front.index');
//});


Route::get('auth/google', [GoogleAuthController::class, 'google_auth'])->name('google_auth');
Route::get('auth/google/callback', [GoogleAuthController::class, 'google_auth_callback'])->name('google_auth_callback');

// Auth::routes();

Route::get('/', [IndexController::class, 'index'])->name('index');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
