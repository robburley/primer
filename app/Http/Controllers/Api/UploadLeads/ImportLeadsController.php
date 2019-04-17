<?php

namespace App\Http\Controllers\Api\UploadLeads;

use App\Http\Controllers\Controller;
use App\Jobs\ImportLeads;
use App\Models\Leads\FileUpload;
use Carbon\Carbon;

class ImportLeadsController extends Controller
{
    public function store(FileUpload $fileUpload)
    {
        $fileUpload->update([
            'import_started_at' => Carbon::now(),
        ]);

        ImportLeads::dispatch($fileUpload);

        return response()
            ->json([
                'file' => $fileUpload->load(['headings']),
            ]);
    }
}
