<?php

namespace App\Http\Controllers\Api\CustomFields;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomForms\FieldGroupRequest;
use App\Models\Campaigns\SelectedCustomFieldGroup;
use App\Models\Leads\CustomFieldGroup;
use Illuminate\Support\Facades\Validator;

class CustomFieldGroupsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param FieldGroupRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(FieldGroupRequest $request)
    {
        auth()->user()->tenant->customFieldGroups()->create([
            'name' => $request->input('name'),
        ]);

        return response()
            ->json($this->response());
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param FieldGroupRequest $request
     * @param CustomFieldGroup  $customFieldGroup
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(FieldGroupRequest $request, CustomFieldGroup $customFieldGroup)
    {
        $customFieldGroup->update([
            'name' => $request->input('name'),
        ]);

        return response()
            ->json($this->response());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CustomFieldGroup $customFieldGroup
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(CustomFieldGroup $customFieldGroup)
    {
        $data = $customFieldGroup->load(['customFields'])->toArray();

        $validator = Validator::make($data, [
            'custom_fields' => function ($attribute, $value, $fail) {
                if (count($value) > 0) {
                    return $fail('You cannot delete a group that has fields');
                }
            },
        ]);

        if ($validator->fails()) {
            return response()
                ->json(['errors' => $validator->messages()], 422);
        }

        $customFieldGroup->update([
            'active' => 0,
        ]);

        SelectedCustomFieldGroup::where([
            'custom_field_group_id' => $customFieldGroup->id,
        ])->delete();

        return response()
            ->json($this->response());
    }

    public function response()
    {
        return auth()->user()->tenant->customFieldGroups()
                                     ->with([
                                         'customFields.rules',
                                         'customFields.bespokeFormField',
                                     ])
                                     ->get();
    }
}
