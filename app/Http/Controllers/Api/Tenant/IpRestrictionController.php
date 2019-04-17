<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\RestrictedIp;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class IpRestrictionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'blacklisted' => 'required|bail',
            'address'     => [
                'required',
                'ip',
                Rule::unique('restricted_ips')->where(function ($query) use ($request) {
                    return $query->where('blacklisted', $request->get('blacklisted'))
                                 ->where('tenant_id', auth()->user()->tenant_id);
                }),
            ],
        ]);

        return auth()->user()
            ->tenant
            ->ipRestrictions()
            ->create([
                'address'     => $request->get('address'),
                'blacklisted' => $request->get('blacklisted'),
            ]);
    }

    public function destroy(RestrictedIp $restrictedIp)
    {
        if (auth()->user()->tenant->id == $restrictedIp->tenant_id) {
            $restrictedIp->delete();

            return response('deleted');
        }

        abort(403);
    }
}
