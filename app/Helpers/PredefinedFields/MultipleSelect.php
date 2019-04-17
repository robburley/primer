<?php

namespace App\Helpers\PredefinedFields;

class MultipleSelect extends PredefinedField
{
    protected $rules = [
        'nullable',
    ];

    public function processData($file)
    {
        return json_encode($this->formatData($file));
    }
}
