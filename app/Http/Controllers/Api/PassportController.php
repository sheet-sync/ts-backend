<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Team;

class PassportController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'team_state' => 'personal',
        ]);
        $token = $user->createToken('Flash')->accessToken;

        $team = new Team();
        $team->owner_id = $user->id;
        $team->name = $request->name;
        $team->save();

        return response()->json(['token' => $token, 'user' => $user], 200);
    }

    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($credentials)) {
            $token = auth()->user()->createToken('Flash')->accessToken;
            return response()->json(['token' => $token, 'user' => auth()->user()], 200);
        } else {
            return response()->json(['error' => 'UnAuthorised'], 401);
        }
    }
}
