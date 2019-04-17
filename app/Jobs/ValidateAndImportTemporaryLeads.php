<?php

namespace App\Jobs;

use App\Models\Leads\FileUpload;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ValidateAndImportTemporaryLeads implements ShouldQueue
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
        try {
            $this->file->update([
                'running' => 1,
            ]);

            $file = file(storage_path('app/' . $this->file->location));

            foreach ($file as $key => $row) {
                if ($key != 0) {
                    $data = collect(str_getcsv($row))
                        ->map(function ($item) {
                            return mb_convert_encoding($item, 'UTF-8', 'UTF-8');
                        });

                    ValidateAndImportTemporaryLead::dispatch($this->file, $data);
                }
            }
        } catch (\Exception $e) {
            $this->file->update([
                'error_at'   => Carbon::now(),
                'error_text' => $e->getMessage(),
            ]);
        }
    }
}
