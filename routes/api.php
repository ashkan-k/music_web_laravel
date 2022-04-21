<?php

use App\Http\Controllers\Admin\AlbumController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\GenreController;
use App\Http\Controllers\Admin\LikeDisLikeController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SingerController;
use App\Http\Controllers\Admin\SubscriberController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\TrackController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UploadController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WishListController;
use App\Http\Controllers\Auth\AuthController;
use Froiden\RestAPI\Facades\ApiRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Authentication API
Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {

    Route::post('register', [AuthController::class , 'register']);
    Route::post('login', [AuthController::class , 'login']);
    Route::post('logout', [AuthController::class , 'logout']);
    Route::post('refresh', [AuthController::class , 'refresh']);
    Route::post('profile', [AuthController::class , 'profile'])->middleware(['auth:api', 'blocked_check']);
    Route::post('profile/update', [AuthController::class , 'profile_update'])->middleware(['auth:api', 'blocked_check']);
    Route::post('account/verify', [AuthController::class , 'verify_account']);
    ## Reset Password##
    Route::post('reset_password', [AuthController::class , 'reset_password']);
    Route::post('reset_password/confirm', [AuthController::class , 'reset_password_confirm']);
    Route::post('reset_password/enter', [AuthController::class , 'reset_password_enter']);

    Route::post('code/resend', [AuthController::class , 'send_code_ajax']);
});

// Admin Panel API
Route::apiResource('admin/users' , UserController::class);
Route::apiResource('admin/transactions' , TransactionController::class)->only(['index', 'show']);

Route::get('admin/blocked_users', [UserController::class, 'blocked_index'])->middleware(['is_superuser']);
Route::post('admin/blocked_users/change/{user}', [UserController::class, 'change_blocked'])->middleware(['is_superuser']);
## Settings Extra Routes ##
ApiRoute::get('admin/panel_settings', 'App\Http\Controllers\Admin\SettingController@panel_settings');

ApiRoute::group(
    [
        'middleware' => ['is_superuser', 'blocked_check'],
        'prefix' => 'admin',
    ],
    function () {
        ApiRoute::resource('singers', SingerController::class);
        ApiRoute::resource('albums', AlbumController::class);
        ApiRoute::resource('tracks', TrackController::class);
        ApiRoute::resource('settings', SettingController::class);
        ApiRoute::resource('comments', CommentController::class);
        ApiRoute::resource('like_dislikes', LikeDisLikeController::class);
        ApiRoute::resource('wish_lists', WishListController::class);
        ApiRoute::resource('uploads', UploadController::class);
        ApiRoute::resource('subscriptions', SubscriptionController::class);
        ApiRoute::resource('subscribers', SubscriberController::class);
        ApiRoute::resource('genres', GenreController::class);

        ## Single Routes ##
        ApiRoute::get('singer_albums/{id}', 'App\Http\Controllers\Admin\AlbumController@singer_albums');

        ## Admin Panel StatisticsInfo ##
        ApiRoute::get('statistics_info', 'App\Http\Controllers\Admin\AdminController@statistics_info');

        ## Admin Bulk Actions ##
        ApiRoute::post('bulk/actions', 'App\Http\Controllers\BulkActionController@admin_bulk');
    }
);
