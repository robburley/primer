<?php

namespace App\Jobs;

use App\Models\Leads\FileUpload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportLeads implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $file;

    /**
     * Create a new job instance.
     *
     * @param FileUpload $file
     */
    public function __construct(FileUpload $file)
    {
        $this->file = $file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->file->update([
            'running' => 1,
        ]);

        $temporaryLeads = $this->file->temporaryLeads()->whereNull('imported_at')->get();

        $temporaryLeads->each(function ($temporaryLead) {
            ImportLead::dispatch($this->file, $temporaryLead);
        });
    }
}
