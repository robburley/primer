<?php

namespace App\Http\Controllers\Api\UploadLeads;

use App\Http\Controllers\Controller;
use App\Jobs\AnalyseFile;
use App\Models\Leads\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FileUploadsController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'file' => 'required|file|mimetypes:text/csv,text/plain|max:40000',
        ]);

        $file = auth()->user()->tenant->fileUploads()->create([
            'name' => $data['file']->getClientOriginalName(),
        ]);

        $file->update([
            'location' => $file->storeFile($data['file']),
        ]);

        AnalyseFile::dispatch($file);

        return response()
            ->json([
                'file' => FileUpload::where('id', $file->id)
                                    ->with(['headings'])
                                    ->first(),
            ]);
    }
}
