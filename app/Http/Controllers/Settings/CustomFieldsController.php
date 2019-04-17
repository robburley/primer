<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Leads\BespokeFormField;

class CustomFieldsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('settings.custom-fields.index', [
            'customFieldGroups' => auth()->user()->tenant->customFieldGroups()
                                                         ->with([
                                                             'customFields.rules',
                                                             'customFields.bespokeFormField',
                                                         ])
                                                         ->get(),
        ]);
    }
}
