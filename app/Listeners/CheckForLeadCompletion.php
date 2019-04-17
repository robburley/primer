<?php

namespace App\Listeners;

use App\Events\LeadCompleted;
use App\Events\LeadSaved;
use App\Models\Leads\Lead;
use Carbon\Carbon;

class CheckForLeadCompletion
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function handle(LeadSaved $event)
    {
        $requiredFields = $event->campaign->selectedCustomFields()
                                          ->with([
                                              'customField.bespokeFormField',
                                          ])
                                          ->where('required_for_completion', 1)
                                          ->get();

        $missingFields = $requiredFields
            ->map(function ($requiredField) use ($event) {
                $field = $requiredField->customField->slug;

                $type = $requiredField->customField->bespokeFormField->type;

                $data = json_decode($event->lead->data);

                $value = $data->{$field} ?? null;

                if ($type == 'checkbox' || $type == 'select-multiple') {
                    return collect(json_decode($value))
                            ->filter(function ($item) {
                                return is_numeric($item);
                            })
                            ->count() == 0;
                }

                if ($type == 'number') {
                    return $value < 0;
                }

                return $value
                    ? false
                    : true;
            })->filter();

        if ($missingFields->count() == 0) {
            $event->lead->campaigns()
                        ->updateExistingPivot($event->campaign->id, [
                            'completed_at' => Carbon::now(),
                            'callback'     => null,
                            'confirmed_at' => $event->campaign->validate_leads ? null : Carbon::now(),
                        ]);

            $lead = Lead::with(['campaigns'])->find($event->lead->id);

            if ($event->campaign->validate_leads) {
                flash('Lead sent for validation!')->success();
            } else {
                flash('Lead Completed!')->success();

                event(new LeadCompleted($event->campaign, $lead));
            }

            return $lead;
        }

        flash('Lead Updated!')->success();
    }
}
