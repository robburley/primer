<?php

namespace App\Policies;

use App\Models\Users\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function view(User $user, User $tenantUser)
    {
        return $user->tenant_id == $tenantUser->tenant_id;
    }
}
