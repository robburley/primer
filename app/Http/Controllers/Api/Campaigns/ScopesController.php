<?php

namespace App\Http\Controllers\Api\Campaigns;

use App\Http\Controllers\Controller;
use App\Models\Campaigns\Campaign;
use App\Models\Campaigns\Scope;
use Illuminate\Http\Request;

class ScopesController extends Controller
{
    public function store(Request $request, Campaign $campaign)
    {
        $request->validate([
            'custom_field_id' => 'required',
            'operator'        => 'required',
            'value'           => 'required',
        ]);

        $scope = $campaign->scopes()->create([
            'custom_field_id' => $request->input('custom_field_id.value'),
            'operator'        => $request->input('operator.value'),
            'value'           => $request->input('value'),
        ]);

        return $scope;
    }

    public function update(Request $request, Campaign $campaign, Scope $scope)
    {
        $request->validate([
            'custom_field_id' => 'required',
            'operator'        => 'required',
            'value'           => 'required',
        ]);

        $scope->update([
            'custom_field_id' => $request->input('custom_field_id.value'),
            'operator'        => $request->input('operator.value'),
            'value'           => $request->input('value'),
        ]);

        return $scope;
    }

    public function destroy(Request $request, Campaign $campaign, Scope $scope)
    {
        $scope->delete();

        return response('Deleted');
    }
}
