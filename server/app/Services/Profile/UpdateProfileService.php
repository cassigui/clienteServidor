<?php
namespace App\Services\Profile;

use App\Models\User;

class UpdateProfileService
{
    public function updateUser(User $user, array $data): void
    {
        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
    }
}