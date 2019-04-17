<?php

namespace App\Helpers\Campaigns\Endpoints;

use App\Mail\SendLead;
use Illuminate\Support\Facades\Mail;

class Email extends Endpoint
{
    public function process()
    {
        Mail::to($this->campaign->endpoint_location)
            ->send(new SendLead($this->resource, $this->campaign));
    }
}
