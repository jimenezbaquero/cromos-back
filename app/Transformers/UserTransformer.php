<?php

namespace App\Transformers;

use App\Models\User;

class UserTransformer
{
    public static function transformToWebIndex(User $user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->roles->isNotEmpty() ? $user->getRoleNames()[0] : '---',
            'created_at' => $user->created_at->format('d/m/Y'),
        ];
    }
}