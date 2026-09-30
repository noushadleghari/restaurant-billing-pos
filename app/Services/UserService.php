<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class UserService
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(array $data, User $user): bool
    {
    
        return $user->update($data);
    }

    public function delete(User $user, int $currentUserId): bool
    {
        if ($user->id === $currentUserId) {
            throw new RuntimeException('You cannot delete your own account.');
        }
        return (bool) $user->delete();
    }
}
