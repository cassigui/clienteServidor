<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function getCurrentUser(): ?User
    {
        return Auth::guard('api')->user();
    }

    public function getUserById(int $userId): ?User
    {
        return User::find($userId);
    }

    public function updateUser(User $user, array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        // 2. Atualiza os dados no modelo
        $user->update($data);

        return $user;
    }

    public function deleteUser(User $user): bool
    {
        return $user->delete();
    }
}