<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\RegisterResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create(
            $request->merge(['password' => bcrypt($request->password)])
                     ->except(['password_confirmation'])
        );

        $token = $user->createToken('fb-api')->plainTextToken;

       return (new RegisterResource($user))
                ->additional(['token' => $token]);
    }

    public function login(Request $request) 
    {
       if (!Auth::attempt($request->all())) {
            abort(401, 'Invalid email or password combination.');
       }

       $user = auth()->user();
       $token = $user->createToken('fb-api')->plainTextToken;
       return (new RegisterResource($user))
                    ->additional(['token' => $token]);
    }
}
