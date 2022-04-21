<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function google_auth()
    {
        return Socialite::driver('google')->redirect();
    }

    public function google_auth_callback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::updateOrCreate([
            'google_id' => $googleUser->id,
        ], [
            'first_name' => $googleUser->name,
            'last_name' => $googleUser->name,
            'email' => $googleUser->email,
            'password' => Hash::make($googleUser->token),
        ]);
    
        $credentials = ['email' => $googleUser->email, 'password' => $googleUser->token];
    
        if (! $token = auth()->setTTL(10080)->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        auth('web')->login($user);
    
        return redirect('http://localhost:4200/google/auth?token=' . $token);
    }
}
