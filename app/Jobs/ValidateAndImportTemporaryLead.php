<?php

namespace App\Jobs;

use App\Exceptions\InvalidFileException;
use App\Models\Leads\FileUpload;
use App\Models\Leads\InvalidLead;
use App\Models\Leads\TemporaryLead;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ValidateAndImportTemporaryLead implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $file;
    protected $row;
    protected $data;
    protected $valid            = true;
    protected $validationErrors = [];

    /**
     * Create a new job instance.
     *
     * @param FileUpload $file
     * @param            $row
     */
    public function __construct(FileUpload $file, $row)
    {
        $this->file = FileUpload::find($file->id);

        $this->row = $row;

        $this->data = $this->validateData();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $processedLeads = $this->file->processed_leads + 1;

            $this->valid
                ? $this->valid($processedLeads)
                : $this->invalid($processedLeads);

            if ($processedLeads == $this->file->total) {
                $this->file = $this->file
                    ->updateAndReturn([
                        'status'  => 2,
                        'running' => 0,
                    ]);

                if ($this->file->valid_leads < 1) {
                    throw new InvalidFileException('No Valid Leads');
                }
            }
        } catch (\Exception $e) {
            $this->file->update([
                'processed_leads' => $this->file->total,
                'error_at'        => Carbon::now(),
                'error_text'      => $e->getMessage(),
                'running'         => 0,
            ]);
        }
    }

    public function validateData()
    {
        return $this->file->headings
            ->map(function ($heading) {
                $customField = $heading->customField;

                if (!$customField) {
                    return null;
                }

                $value = $customField->getValue($this->row[$heading->array_key]);

                $validator = $customField->validateData($value, $this->file);

                if ($validator->fails()) {
                    $this->valid = false;

                    array_push($this->validationErrors, $validator->errors());
                }

                return [
                    'key'   => $customField->slug,
                    'value' => $value ?? '',
                ];
            })
            ->keyBy('key')
            ->filter()
            ->map(function ($item) {
                return $item['value'];
            });
    }

    public function valid($processedLeads)
    {
        $this->file = $this->file
            ->updateAndReturn([
                'processed_leads' => $processedLeads,
                'valid_leads'     => $this->file->valid_leads + 1,
            ]);

        TemporaryLead::create([
            'file_upload_id' => $this->file->id,
            'data'           => $this->data->toJson(),
        ]);
    }

    public function invalid($processedLeads)
    {
        $this->file = $this->file
            ->updateAndReturn([
                'processed_leads' => $processedLeads,
                'invalid_leads'   => $this->file->invalid_leads + 1,
            ]);

        InvalidLead::create([
            'file_upload_id'    => $this->file->id,
            'data'              => collect($this->row)->toJson(),
            'validation_errors' => collect($this->validationErrors)->toJson(),
        ]);
    }
}
