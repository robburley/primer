<?php

namespace App\Http\Controllers\Api\Campaigns;

use App\Http\Controllers\Controller;
use App\Models\Campaigns\Campaign;
use App\Models\Campaigns\SelectedCustomField;
use App\Models\Campaigns\SelectedCustomFieldGroup;
use App\Models\Leads\CustomFieldGroup;
use Illuminate\Http\Request;

class SelectedCustomGroupsController extends Controller
{
    public function index(Campaign $campaign)
    {
        return $campaign->selectedCustomFieldGroups()
                        ->with([
                            'customFieldGroup',
                            'selectedCustomFields.customField.rules',
                            'selectedCustomFields.customField.bespokeFormField',
                        ])
                        ->get();
    }

    public function store(Request $request, Campaign $campaign)
    {
        $request->validate([
            'group.value' => 'required',
        ]);

        $group = CustomFieldGroup::with([
            'customFields',
            'selectedCustomFieldGroups',
        ])->find($request->group['value']);

        $selectedGroup = $group->selectedCustomFieldGroups()->create([
            'campaign_id'           => $campaign->id,
            'custom_field_group_id' => $group->id,
            'order'                 => $campaign->selectedCustomFieldGroups()->count(),
        ]);

        foreach ($group->customFields as $order => $field) {
            $selectedGroup->selectedCustomFields()->create([
                'selected_custom_field_group_id' => $selectedGroup->id,
                'custom_field_id'                => $field->id,
                'show_to_lead_generator'         => 1,
                'show_to_customers'              => 0,
                'required_for_completion'        => 1,
                'show_to_confirmer'              => 1,
                'order'                          => $order,
            ]);
        }

        return $this->index($campaign);
    }

    public function update(Request $request, Campaign $campaign)
    {
        foreach ($request->groups as $groupOrder => $group) {
            $selectedGroup = SelectedCustomFieldGroup::find($group['id']);

            $selectedGroup->update([
                'order' => $groupOrder,
            ]);

            foreach ($group['selected_custom_fields'] as $fieldOrder => $field) {
                $selectedField = SelectedCustomField::find($field['id']);

                $selectedField->update([
                    'order'                   => $fieldOrder,
                    'show_to_lead_generator'  => $field['show_to_lead_generator'],
                    'show_to_customers'       => $field['show_to_customers'],
                    'required_for_completion' => $field['required_for_completion'],
                    'show_to_confirmer'       => $field['show_to_confirmer'],
                ]);
            }
        }

        return $this->index($campaign);
    }

    public function destroy(Request $request, Campaign $campaign, SelectedCustomFieldGroup $selectedCustomFieldGroup)
    {
        try {
            $selectedCustomFieldGroup->selectedCustomFields()->delete();

            $selectedCustomFieldGroup->delete();
        } catch (\Exception $e) {
            return response('error', 500);
        }

        return response('Deleted');
    }
}
