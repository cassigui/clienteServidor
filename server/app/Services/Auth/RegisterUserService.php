<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class RegisterUserService
{
    public function createUser(array $data): User
    {
        info($data);
        $user = User::create([
            'username' => strtoupper($data['username']),
            'name' => strtoupper($data['name']),
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'experience' => $data['experience'] ?? null,
            'education' => $data['education'] ?? null,
        ]);

        event(new Registered($user));

        return $user;
    }
}