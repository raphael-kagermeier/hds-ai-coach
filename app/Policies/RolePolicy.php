<?php

namespace App\Policies;

use App\Traits\SuperAdminOnly;

class RolePolicy
{
    use SuperAdminOnly;
}
