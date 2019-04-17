<?php

namespace App\Http\Controllers\Api\UploadLeads;

use App\Http\Controllers\Controller;
use App\Jobs\ValidateAndImportTemporaryLeads;
use App\Models\Leads\FileUpload;

class ValidateAndImportTemporaryLeadsController extends Controller
{
    public function store(FileUpload $fileUpload)
    {
        ValidateAndImportTemporaryLeads::dispatch($fileUpload);

        return $fileUpload->load(['headings']);
    }
}
