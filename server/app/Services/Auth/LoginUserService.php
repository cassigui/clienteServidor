<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Auth;

class LoginUserService
{
    public function attemptLogin(array $credentials): string|false
    {

        if ($token = Auth::guard('api')->attempt($credentials)) {
            return $token;
        }

        return false;
    }
}