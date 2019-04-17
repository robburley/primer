<?php

namespace App\Http\Controllers\Api\UploadLeads;

use App\Http\Controllers\Controller;
use App\Models\Leads\FileUpload;
use App\Models\Leads\FileUploadHeading;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MapFieldsController extends Controller
{
    public function store(FileUpload $fileUpload, Request $request)
    {
        $headings = collect($request->all())
            ->map(function ($item) {
                return $item['custom_field_id']['value'] ?? null;
            })
            ->filter()
            ->count();

        $validator = Validator::make(['headings' => $headings], [
            'headings' => function ($attribute, $value, $fail) {
                if ($value == 0) {
                    return $fail('You must select at least one custom field to map');
                }
            },
        ]);

        if ($validator->fails()) {
            return response()
                ->json(['errors' => $validator->messages()], 422);
        }

        foreach ($request->all() as $heading) {
            $current = FileUploadHeading::find($heading['id']);

            $current && $current->update([
                'custom_field_id' => $heading['custom_field_id']
                    ? $heading['custom_field_id']['value']
                    : null,
            ]);
        }

        $fileUpload->update([
            'fields_mapped_at' => Carbon::now(),
        ]);

        return $fileUpload->load(['headings']);
    }
}
