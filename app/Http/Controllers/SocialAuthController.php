<?php

namespace App\Http\Controllers;

use App\Models\LinkedSocialAccount;
use App\User;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Team;
use Ausi\SlugGenerator\SlugGenerator;

class SocialAuthController extends Controller
{
    public function redirect() {
        $params = [
            'access_type' => 'offline',
            'include_granted_scopes' => 'true'
        ];
        return Socialite::driver('google')->redirectUrl(config('services.google.redirect'))
                ->with($params)->stateless()->redirect();
    }

    public function callback() {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            $existUser = User::where('email',$googleUser->email)->first();


            if($existUser) {
                Auth::loginUsingId($existUser->id);
            }
            else {

                $generator = new SlugGenerator;
                $tail = ' ' . Str::random(7);

                $user = new User;
                $user->name = $googleUser->name;
                $user->email = $googleUser->email;
                $user->password = bcrypt(Str::random(12));
                $user->save();

                $token = $user->createToken('Flash')->accessToken;

                $social = new LinkedSocialAccount;
                $social->user_id = $user->id;
                $social->provider_id = $googleUser->getId();
                $social->provider_name = 'google';
                $social->access_token = $googleUser->token;
                $social->save();

                $team = new Team();
                $team->owner_id = $user->id;
                $team->name = $googleUser->name;
                $team->slug = $generator->generate($googleUser->name . $tail);
                $team->save();

                return response()->json(['token' => $token, 'user' => $user, 'google' => $googleUser], 200);
            }
        }
        catch (Exception $e) {
            return 'error';
        }
    }
}
