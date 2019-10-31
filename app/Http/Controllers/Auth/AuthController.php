<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperController;
use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AuthController extends HelperController
{
    public function signup(Request $request)
    {
        $rules = [
            'email'       => 'required|string|email|unique:users',
            'password'    => 'required|string|confirmed',
            'username'  => 'required|string|unique:users',
        ];

        if($this->jgmo($request, $rules)){
            return $this->jgmo($request, $rules);
        }

        $user = new User([
            'email'    => $request->email,
            'username'    => $request->username,
            'password' => bcrypt($request->password),
        ]);
        $user->save();
        return response()->json([
            'message' => 'Successfully created user!'], 201);
    }

    public function login(Request $request)
    {
        $rules = [
            'email'       => 'required|string|email',
            'password'    => 'required|string',
            'remember_me' => 'boolean',
        ];

        if($this->jgmo($request, $rules)){
            return $this->jgmo($request, $rules);
        }

        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {

            return response()->json([
                'errors' => ['Email o Credencial incorrecta.']], 422);
        }
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse(
                $tokenResult->token->expires_at)
                    ->toDateTimeString(),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 
            'Successfully logged out']);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
