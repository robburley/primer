<?php

namespace App\Http\Controllers\Api\UploadLeads;

use App\Http\Controllers\Controller;
use App\Models\Leads\FileUpload;
use Carbon\Carbon;

class DiscardLeadsController extends Controller
{
    public function store(FileUpload $fileUpload)
    {
        $fileUpload->update([
            'error_at'   => Carbon::now(),
            'error_text' => 'File Discarded',
        ]);

        return response()
            ->json([
                'file' => $fileUpload->load(['headings']),
            ]);
    }
}
