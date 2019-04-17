<?php

namespace App\Http\Controllers\Api\CustomFields;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomForms\FieldRequest;
use App\Models\Campaigns\SelectedCustomField;
use App\Models\Leads\CustomField;
use App\Models\Leads\CustomFieldGroup;

class CustomFieldsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param CustomFieldGroup $customFieldGroup
     * @param FieldRequest     $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomFieldGroup $customFieldGroup, FieldRequest $request)
    {
        $field = $customFieldGroup->customFields()
                                  ->whereNull('campaign_id')
                                  ->create([
                                      'name'                  => $request->input('name'),
                                      'placeholder'           => $request->input('placeholder'),
                                      'default'               => $request->input('default'),
                                      'options'               => $request->input('options'),
                                      'bespoke_form_field_id' => $request->input('bespoke_form_field_id'),
                                  ]);

//        Schema::table('leads', function (Blueprint $table) use ($field) {
//            $table->string('data_' . $field->slug)->virtualAs('data->>"$.' . $field->slug . '"');
//            $table->index('data_' . $field->slug);
//        });
//
//        Schema::table('temporary_leads', function (Blueprint $table) use ($field) {
//            $table->string('data_' . $field->slug)->virtualAs('data->>"$.' . $field->slug . '"');
//            $table->index('data_' . $field->slug);
//        });

        foreach ($request->input('validation_rules') as $rule) {
            $field->rules()->attach($rule['rule_id'], ['argument' => $rule['argument']]);
        }

        foreach ($customFieldGroup->selectedCustomFieldGroups as $selected) {
            $selected->selectedCustomFields()->create([
                'custom_field_id'         => $field->id,
                'show_to_lead_generator'  => 0,
                'show_to_customers'       => 0,
                'required_for_completion' => 0,
                'show_to_confirmer'       => 0,
                'order'                   => $selected->selectedCustomFields->count(),
            ]);
        }

        return response()
            ->json($this->response());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CustomFieldGroup $customFieldGroup
     * @param FieldRequest     $request
     * @param CustomField      $customField
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CustomFieldGroup $customFieldGroup, FieldRequest $request, CustomField $customField)
    {
        $customField->update([
            'name'                  => $request->input('name'),
            'placeholder'           => $request->input('placeholder'),
            'default'               => $request->input('default'),
            'options'               => $request->input('options'),
            'bespoke_form_field_id' => $request->input('bespoke_form_field_id'),
        ]);

        $customField->rules()->detach();

        foreach ($request->input('validation_rules') as $rule) {
            $customField->rules()->attach($rule['rule_id'], ['argument' => $rule['argument']]);
        }

        return response()
            ->json($this->response());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CustomFieldGroup $customFieldGroup
     * @param CustomField      $customField
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(CustomFieldGroup $customFieldGroup, CustomField $customField)
    {
        $customField->update([
            'active' => 0,
        ]);

        SelectedCustomField::where([
            'custom_field_id' => $customField->id,
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
