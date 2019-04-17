<?php

namespace App\Http\Controllers\Api\UploadLeads\Process;

use App\Http\Controllers\Controller;
use App\Models\Leads\FileUpload;

class ValidateAndImportTemporaryLeadController extends Controller
{
    public function store(FileUpload $fileUpload)
    {
        if (($fileUpload->processed_leads == $fileUpload->total) || $fileUpload->error_at) {
            return response()
                ->json([
                    'file' => $fileUpload->load(['headings']),
                ]);
        }

        abort(501);
    }
}
