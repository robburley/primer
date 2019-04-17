<?php

namespace App\Helpers\PredefinedFields;

class Number extends PredefinedField
{
    protected $rules = [
        'nullable',
    ];

    public function formatData($data)
    {
        return floatval($data);
    }
}
