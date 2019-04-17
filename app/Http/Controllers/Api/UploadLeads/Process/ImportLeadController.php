<?php

namespace App\Http\Controllers\Api\UploadLeads\Process;

use App\Http\Controllers\Controller;
use App\Models\Leads\FileUpload;

class ImportLeadController extends Controller
{
    public function store(FileUpload $fileUpload)
    {
        if (($fileUpload->imported_leads == $fileUpload->valid_leads) || $fileUpload->error_at) {
            return response()
                ->json([
                    'file' => $fileUpload->load(['headings']),
                ]);
        }

        abort(501);
    }
}
