<?php

namespace App\Actions\Auth;

use App\Models\User;

class CreateApiTokenAction
{
    public function execute(User $user): string
    {
        return $user->createToken('auth_token')->plainTextToken;
    }
}
