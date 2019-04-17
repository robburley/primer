<?php

namespace App\Policies;

use App\Models\Leads\CustomFieldGroup;
use App\Models\Users\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomFieldGroupPolicy
{
    use HandlesAuthorization;

    public function view(User $user, CustomFieldGroup $customFieldGroup)
    {
        return $user->tenant_id == $customFieldGroup->tenant_id;
    }
}
