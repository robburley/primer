<?php

namespace App\Helpers\Scopes;

use Carbon\Carbon;

class FormatValue
{
    protected $value;
    protected $operator;
    protected $customField;
    protected $values;

    public function __construct($customField, $scope)
    {
        $this->operator = $scope->operator;

        $this->value = $scope->value;

        $this->customField = $scope->customField;
    }

    public function handle()
    {
        $this->values = collect(array_map('trim', explode(',', $this->value)));

        switch ($this->operator) {
            case 'in':
                return $this->values->map(function ($value) {
                    $this->formatValue($value);
                })->toArray();

                break;
            case 'between':
                return $this->values->map(function ($value) {
                    return $this->formatValue($value);
                })->toArray();

                break;
            case 'LIKE':
                return strtolower("%$this->value%");

                break;
            default:
                return $this->formatValue($this->value);

        }
    }

    public function formatValue($value)
    {
        switch ($this->customField->bespoke_form_field_id) {
            case 2:
                return Carbon::createFromFormat('d/m/Y', $value)
                             ->startOfDay()
                             ->format('Y-m-d h:i:s.u');

                break;
            case 4:
                return floatval($value);

                break;
            default:
                return $value;
        }
    }
}
