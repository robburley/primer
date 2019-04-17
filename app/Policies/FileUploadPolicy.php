<?php

namespace App\Policies;

use App\Models\Leads\FileUpload;
use App\Models\Users\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FileUploadPolicy
{
    use HandlesAuthorization;

    public function view(User $user, FileUpload $fileUpload)
    {
        return $user->tenant_id == $fileUpload->tenant_id;
    }
}
