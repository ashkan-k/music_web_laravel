<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Traits\Responses;
use App\Http\Traits\Uploader;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use Uploader , Responses;

    public function index()
    {
        return User::Where('is_blocked', false)->Filter(\request('search'))->paginate(env('PAGINATION_NUMBER'));
    }

    public function blocked_index()
    {
        return User::Where('is_blocked', true)->Filter(\request('search'))->paginate(env('PAGINATION_NUMBER'));
    }

    public function change_blocked(Request $request, User $user)
    {
        $user->update(['is_blocked' => $request->is_blocked]);
        $this->SuccessResponse($user);
    }

    public function store(UserRequest $request)
    {
        $avatar = $request->hasFile('avatar') ?
            $this->UploadFile($request->file('avatar') , 'avatars', $request->email) : null;

        $request['password'] = Hash::make($request->password);

        $user = User::create(array_merge($request->all() , ['avatar' => $avatar]));
        $this->SuccessResponse($user);
    }

    public function show(User $user)
    {
        return response($user);
    }

    public function update(UserRequest $request, User $user)
    {
        $avatar = $request->hasFile('avatar') ?
            $this->UploadFile($request->file('avatar') , 'avatars', $request->email) : $user->avatar;

        $request['password'] = $request->password ? Hash::make($request->password) : $user->password;

        $user->update(array_merge($request->all() , ['avatar' => $avatar]));
        $this->SuccessResponse($user);
    }

    public function destroy(User $user)
    {
        $user->delete();
        $this->SuccessResponse('کاربر با موفقیت حذف شد!');
    }
}
