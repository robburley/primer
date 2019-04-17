<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;

class UploadLeadsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('settings.upload-leads.index', [
            'files'        => auth()->user()->tenant->fileUploads()
                                                    ->with(['headings'])
                                                    ->get(),
            'customFields' => auth()->user()->tenant->customFields()
                                                    ->whereNull('custom_fields.deactivated_at')
                                                    ->get(),
        ]);
    }
}
