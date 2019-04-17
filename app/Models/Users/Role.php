<?php

namespace App\Models\Users;

use App\Models\Traits\CommonScopes;
use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    use CommonScopes;

    protected $casts = [
        'deactivated_at' => 'timestamp',
    ];
}
