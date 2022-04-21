<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\AuthHelpers;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\UserRequest;
use App\Http\Traits\Responses;
use App\Http\Traits\Uploader;
use App\Models\Code;
use App\Models\User;
use App\Notifications\UserResetPasswordMail;
use App\Notifications\UserVerificationMail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use Uploader , Responses, AuthHelpers;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'verify_account', 'send_code_ajax', 'reset_password', 'reset_password_confirm', 'reset_password_enter']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(UserRequest $request)
    {
        $request['is_superuser'] = false;
        $user = User::create(array_merge($request->all() , ['password' => Hash::make($request->password)]));

        $this->SendCode($user, UserVerificationMail::class);

        return $this->SuccessResponse('ثبت نام شما با موفقیت انجام شد.');
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
        $remember_time = request('remember_me') ? 10080 : 60;  ## 168 = 1 weak , 60 = 1 hour

        if (! $token = auth()->setTTL($remember_time)->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if (auth()->user()->is_blocked){
            auth()->logout();
            return $this->FailResponse('کاربر گرامی شما توسط مدیر سایت مسدود شده اید و امکان ورود ندارید! ', 403);
        }

        if (!auth()->user()->email_verified_at){
            $this->SendCode(auth()->user(), UserVerificationMail::class);
            auth()->logout();
            return $this->FailResponse('کاربر گرامی لطفا ابتدا حساب کاربری خود را تایید کنید! ', 450);
        }

        auth('web')->loginUsingId(auth()->user()->id);

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        $data = $this->GetUserFullData();
        return response()->json($data);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile_update(ProfileRequest $request)
    {
        $avatar = $request->hasFile('avatar') ?
            $this->UploadFile($request->file('avatar') , 'avatars', $request->email) : auth()->user()->avatar;

        $request['password'] = $request->password ? Hash::make($request->password) : auth()->user()->password;

        $data = array_merge($request->all() , ['avatar' => $avatar]);
        auth()->user()->update($data);

        return $this->SuccessResponse($this->GetUserFullData());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth('api')->user()
        ]);
    }

    /**
     * Get a JWT via given credentials.verify_account
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify_account(Request $request)
    {
        $this->validate($request, $this->verify_validators);
        $this->verify_code($request->code);

        $user = User::whereEmail($request->email)->firstOrFail();
        $user->update(['email_verified_at' => Carbon::now()]);

        if (! $token = auth()->setTTL(10080)->login($user)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if (auth()->user()->is_blocked){
            auth()->logout();
            return $this->FailResponse('کاربر گرامی شما توسط مدیر سایت مسدود شده اید و امکان ورود ندارید! ', 403);
        }

        auth('web')->loginUsingId(auth()->user()->id);

        $this->change_is_used($user);

        return $this->respondWithToken($token);
    }

    ## Reset Password ##
    public function reset_password(Request $request)
    {
        $this->validate($request, $this->reset_password_email_validators);

        $user = User::whereEmail($request->email)->first();
        $this->SendCode($user, UserResetPasswordMail::class);

        return $this->SuccessResponse('پیامک حاوی کد احراز هویت برای شما ارسال شد.');
    }

    public function reset_password_confirm(Request $request)
    {
        $this->validate($request, $this->verify_validators);
        $this->verify_code($request->code);

        $user = Code::whereCode($request->code)->first()->user;
        $this->change_is_used($user);

        return $this->SuccessResponse($request->code);
    }

    public function reset_password_enter(Request $request)
    {
        $this->validate($request, $this->reset_password_enter_validators);
        $code = $request->code;

        if ($code) {
            $user = Code::whereCode($code)->first()->user;
            $user->update(['password' => Hash::make($request->password)]);

            return $this->SuccessResponse('تغییر رمز عبور شما با موفقیت انجام شد . اکنون میتوانید وارد شوید.');
        }

        throw ValidationException::withMessages(['code' => 'زمان بازیابی رمز عبور منقضی شده است! ، دوباره درخواست کنید']);
    }

    #########################################################################

    public function send_code_ajax(Request $request)
    {
        $user = User::whereEmail($request->email)->firstOrFail();

        $this->SendCode($user, $request->notification);
        return $this->SuccessResponse(['message' => 'کد جدید ارسال شد.']);
    }

    #########################################################################

    private function GetUserFullData()
    {
        $data = auth()->user();
        $data['subscription'] = User::find($data->id)->subscription;

        return $data;
    }
}
