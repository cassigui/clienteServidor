<?php
namespace App\Services\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordService
{
    public function updatePassword(User $user, array $data): void
    {
        $user->update([
            'password' => Hash::make($data['password']),
        ]);
    }
}