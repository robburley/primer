<?php

namespace App\Http\Controllers\Files;

use App\Http\Controllers\Controller;
use App\Models\Leads\LeadFile;
use App\Models\Tenant\Tenant;

class LeadFilesController extends Controller
{
    public function show(Tenant $tenant, LeadFile $leadFile, $hash)
    {
        if ($leadFile->tenant_id != $tenant->id || $hash != $leadFile->hash) {
            abort(404);
        }

        return response()->file(storage_path($leadFile->downloadPath()));
    }
}
