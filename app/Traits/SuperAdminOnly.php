<?php

namespace App\Traits;

use App\Models\User;

trait SuperAdminOnly
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin();
    }
}
