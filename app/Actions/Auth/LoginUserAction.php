<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginUserAction
{
    public function execute(array $credentials): ?User
    {
        if (! Auth::attempt($credentials)) {
            return null;
        }

        return Auth::user();
    }
}
