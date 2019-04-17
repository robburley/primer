<?php

namespace App\Jobs;

use App\Exceptions\InvalidFileException;
use App\Models\Leads\FileUpload;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AnalyseFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $file;
    protected $total    = 0;
    protected $headings = [];

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
            $file = file(storage_path('app/' . $this->file->location));

            collect(str_getcsv($file[0]))
                ->map(function ($header, $key) {
                    $this->file->headings()
                               ->create([
                                   'name'      => $header,
                                   'array_key' => $key,
                               ]);
                });

            if (($count = count($file) - 1) < 1) {
                throw new InvalidFileException('Invalid File');
            }

            $this->file->update([
                'total'       => $count,
                'analysed_at' => Carbon::now(),
                'status'      => 1,
            ]);
        } catch (\Exception $e) {
            $this->file->update([
                'error_at'   => Carbon::now(),
                'error_text' => 'Invalid File',
                'status'     => 4,
            ]);
        }
    }
}
