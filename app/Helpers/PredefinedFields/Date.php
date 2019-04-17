<?php

namespace App\Helpers\PredefinedFields;

use Carbon\Carbon;

class Date extends PredefinedField
{
    protected $rules = [
        'nullable',
        'date',
    ];

    public function formatData($data)
    {
        try {
            return Carbon::createFromFormat('d/m/Y', $data)->format('Y-m-d h:i:s.u');
        } catch (\Exception $e) {
            return $data;
        }
    }
}
