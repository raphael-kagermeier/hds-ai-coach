<?php

namespace App\Policies;

use App\Traits\SuperAdminOnly;

class PermissionPolicy
{
    use SuperAdminOnly;
}
