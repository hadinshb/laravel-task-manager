<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Auth\CreateApiTokenAction;
use App\Actions\Auth\LoginUserAction;
use App\Actions\Auth\RegisterUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request, RegisterUserAction $registerUser, CreateApiTokenAction $createToken)
    {
        $user = $registerUser->execute($request->validated());
        $token = $createToken->execute($user);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function login(LoginRequest $request, LoginUserAction $loginUser, CreateApiTokenAction $createToken)
    {
        $user = $loginUser->execute($request->validated());

        if (! $user) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }

        $token = $createToken->execute($user);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}
