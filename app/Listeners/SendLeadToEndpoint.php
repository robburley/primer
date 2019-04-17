<?php

namespace App\Listeners;

use App\Events\LeadCompleted;
use App\Helpers\Campaigns\Endpoints\Email;
use App\Helpers\Campaigns\Endpoints\JsonApi;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendLeadToEndpoint implements ShouldQueue
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

    public function handle(LeadCompleted $event)
    {
        $leadAssignment = $event->lead->campaignData($event->campaign);

        $user = $event->campaign->validate_leads
            ? $leadAssignment->validator
            : $leadAssignment->assigned;

        $leadAssignment->notes()->create([
            'note'    => 'Record Sent',
            'user_id' => $user->id,
        ]);

        try {
            switch ($event->campaign->endpoint_type) {
                case 1:
                    (new JsonApi($event->lead, $event->campaign))->process();

                    break;
                case 2:
                    (new Email($event->lead, $event->campaign))->process();

                    break;
            }
            
            $event->lead->campaigns()
                        ->updateExistingPivot(
                            $event->campaign->id,
                            [
                                'sent_at' => Carbon::now(),
                            ]
                        );
        } catch (\Exception $e) {
            return Log::info($e->getMessage());
        }
    }
}
