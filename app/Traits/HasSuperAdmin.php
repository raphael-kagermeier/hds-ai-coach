<?php

namespace App\Traits;

use App\Enums\RolesEnum;
use Spatie\Permission\Traits\HasRoles;

trait HasSuperAdmin
{
    use HasRoles;

    public function isSuperAdmin(): bool
    {
        return $this->hasRole(RolesEnum::SuperAdmin->value);
    }
}
