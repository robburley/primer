<?php

namespace App\Helpers\PredefinedFields;

use App\Models\Leads\LeadFile;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;

class File extends PredefinedField
{
    protected $rules = [
        'nullable',
    ];

    public function processData($file)
    {
        if ($file instanceof UploadedFile && auth()->user()) {
            $location = '/lead-files/' . Carbon::now()->format('dmyhis');

            $fileName = $file->getClientOriginalName();

            $file->storeAs('tenants/' . auth()->user()->tenant->id . $location, $fileName);

            $leadFile = LeadFile::create([
                'tenant_id' => auth()->user()->tenant->id,
                'location'  => $location,
                'name'      => $fileName,
                'hash'      => str_random(64),
            ]);

            return $leadFile->id;
        }

        return $this->formatData($file);
    }

    public function formatData($file)
    {
        if ($file instanceof UploadedFile) {
            return $file;
        }

        $leadFile = LeadFile::find($file);

        return $leadFile
            ? $leadFile->generateLink()
            : null;
    }
}
