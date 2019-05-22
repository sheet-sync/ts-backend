<?php

namespace App\Http\Controllers;

use App\Models\LinkedSocialAccount;
use App\User;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirect() {
        $params = [
            'access_type' => 'offline',
            'include_granted_scopes' => true
        ];
        return Socialite::driver('google')->with($params)->stateless()->redirect();
    }

    public function callback() {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            $existUser = User::where('email',$googleUser->email)->first();


            if($existUser) {
                Auth::loginUsingId($existUser->id);
            }
            else {
                $user = new User;
                $user->name = $googleUser->name;
                $user->email = $googleUser->email;
                $user->password = bcrypt(Str::random(12));
                $user->save();

                $social = new LinkedSocialAccount;
                $social->user_id = $user->id;
                $social->provider_id = $googleUser->getId();
                $social->provider_name = 'google';
                $social->access_token = $googleUser->token;
                $social->save();

                return response()->json($user);
            }
        }
        catch (Exception $e) {
            return 'error';
        }
    }
}
