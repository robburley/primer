<?php

namespace App\Events;

use App\Models\Campaigns\Campaign;
use App\Models\Leads\Lead;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeadCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $lead;
    public $campaign;

    public function __construct(Campaign $campaign, Lead $lead)
    {
        $this->lead = $lead;

        $this->campaign = $campaign->load(['tenant']);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
