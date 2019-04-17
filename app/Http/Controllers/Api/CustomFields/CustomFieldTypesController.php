<?php

namespace App\Http\Controllers\Api\CustomFields;

use App\Http\Controllers\Controller;
use App\Models\Leads\BespokeFormField;

class CustomFieldTypesController extends Controller
{
    public function index()
    {
        return BespokeFormField::orderBy('name')
                               ->with([
                                   'rules',
                               ])
                               ->get();
    }
}
