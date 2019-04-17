<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;

class SetupController extends Controller
{
    public function edit()
    {
        return view('settings.setup.edit', [
            'tenant' => auth()->user()->tenant,
        ]);
    }
}
