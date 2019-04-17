<?php

namespace App\Jobs;

use App\Models\Leads\FileUpload;
use App\Models\Leads\Lead;
use App\Models\Leads\TemporaryLead;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportLead implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $file;
    protected $lead;

    /**
     * Create a new job instance.
     *
     * @param FileUpload    $file
     * @param TemporaryLead $lead
     */
    public function __construct(FileUpload $file, TemporaryLead $lead)
    {
        $this->file = $file;

        $this->lead = $lead;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        Lead::create([
            'data'      => $this->lead->data,
            'tenant_id' => $this->file->tenant_id,
        ]);

        $file = FileUpload::find($this->file->id);

        $importedLeads = $file->imported_leads + 1;

        $this->file
            ->update([
                'imported_leads' => $importedLeads,
            ]);

        if ($importedLeads == $file->valid_leads) {
            $this->file->update([
                'status'  => 3,
                'running' => 0,
            ]);
        }
    }
}
