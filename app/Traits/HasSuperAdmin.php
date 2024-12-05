<?php

namespace App\Traits;

use Spatie\Permission\Traits\HasRoles;

trait HasSuperAdmin
{
    use HasRoles;

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super-admin');
    }
}
