<?php

namespace App\Helpers\Scopes;

class ScopeQueryBuilder
{
    protected $scope;
    protected $query;

    public function __construct($scope, $query)
    {
        $this->scope = $scope;

        $this->query = $query;
    }

    public function handle()
    {
        $this->formatQuery(
            'data->' . $this->scope->customField->slug,
            $this->scope->operator,
            (new FormatValue($this->scope->customField, $this->scope))->handle()
        );
    }

    public function formatQuery($field, $operator, $value)
    {
        $fieldFormatted = 'data->\'$."' . $this->scope->customField->slug . '"\'';

        switch ($this->scope->operator) {
            case 'in':
                if (count($value) > 1) {
                    return $this->query->whereIn($field, $value);
                } else {
                    $operator = '=';
                }

                break;
            case 'between':
                return $this->scope->customField->bespoke_form_field_id == 2
                    ? $this->query->whereRaw("JSON_UNQUOTE($fieldFormatted) between \"$value[0]\" and \"$value[1]\"")
                    : $this->query->whereBetween($field, $value);

                break;
            case 'LIKE':
                return $this->query->whereRaw("LOWER($fieldFormatted) LIKE ?", $value);

                break;
        }

        return $this->query->where($field, $operator, $value);
    }
}
