<?php

namespace App\Rules;

use App\Models\Leads\Lead;
use App\Models\Leads\TemporaryLead;
use Illuminate\Contracts\Validation\Rule;

class FileValidationUniqueJson implements Rule
{
    protected $currentFile;

    /**
     * Create a new rule instance.
     * @param $currentFile
     */
    public function __construct($currentFile)
    {
        $this->currentFile = $currentFile;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $leads = Lead::where('data->' . $attribute, $value)
                     ->when(request()->lead, function ($query) {
                         return $query->where('id', '<>', request()->lead->id);
                     })
                     ->count();

        $temporaryLeads = TemporaryLead::where('data->' . $attribute, $value)
                                       ->where('file_upload_id', $this->currentFile->id)
                                       ->count();

        return $leads == 0 && $temporaryLeads == 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Duplicate record';
    }
}
