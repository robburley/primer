<?php

namespace App\Helpers\PredefinedFields;

use App\Models\Leads\BespokeFormField;
use App\Models\Leads\CustomField;

class PredefinedField
{
    protected $rules = [];
    protected $bespokeFormField;
    protected $customField;

    public function __construct(BespokeFormField $bespokeFormField)
    {
        $this->bespokeFormField = $bespokeFormField;
    }

    public function formatData($data)
    {
        return $data;
    }

    public function getValidationRules()
    {
        return $this->rules;
    }

    public function render(CustomField $customField, $value = null, $readonly = false)
    {
        $view = $readonly
            ? 'form-fields.read-only.' . $this->bespokeFormField->type
            : 'form-fields.' . $this->bespokeFormField->type;

        try {
            return view($view, [
                'customField' => $customField,
                'value'       => $this->formatData($value),
            ]);
        } catch (\Exception $e) {
            return view('errors.form-field');
        }
    }

    public function processData($data)
    {
        return $this->formatData($data);
    }
}
