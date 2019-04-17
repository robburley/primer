<?php

namespace App\Mail;

use App\Models\Campaigns\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendLead extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $campaign;

    /**
     * Create a new message instance.
     *
     * @param          $data
     * @param Campaign $campaign
     */
    public function __construct($data, Campaign $campaign)
    {
        $this->data = $data;

        $this->campaign = $campaign;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.send-lead');
    }
}
