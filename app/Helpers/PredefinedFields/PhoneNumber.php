<?php

namespace App\Helpers\PredefinedFields;

use libphonenumber\NumberParseException;
use Propaganistas\LaravelPhone\PhoneNumber as PhoneNumberGenerator;

class PhoneNumber extends PredefinedField
{
    protected $rules = [
        'nullable',
        'phone:AUTO,GB',
    ];

    public function formatData($data)
    {
        try {
            return PhoneNumberGenerator::make($data, 'GB')->formatNational();
        } catch (NumberParseException $e) {
            return $data;
        }
    }
}
