<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IpRestrictionTypeController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'type' => 'required|boolean',
        ]);

        auth()->user()
            ->tenant
            ->update([
                'restriction_type' => $request->get('type'),
            ]);

        return response('updated');
    }
}
