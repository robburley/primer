<?php

namespace App\Http\Requests\CustomForms;

use Illuminate\Foundation\Http\FormRequest;

class FieldRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'                  => 'required',
            'bespoke_form_field_id' => 'required',
        ];
    }

    protected function prepareForValidation()
    {
        $input = $this->all();

        $args = collect($input['validation_args'])
            ->filter()
            ->map(function ($item, $key) {
                return [
                    'rule_id'  => $key,
                    'argument' => $item,
                ];
            });

        $input['validation_rules'] = collect($input['validation_rules'])
            ->filter()
            ->flip()
            ->map(function ($item, $key) use ($args) {
                $arg = $args->where('rule_id', $key)->first();

                return [
                    'rule_id'  => $key,
                    'argument' => $arg ? $arg['argument'] : null,
                ];
            });

        $input['bespoke_form_field_id'] = $input['bespoke_form_field_id']
            ? $input['bespoke_form_field_id']['id']
            : null;

        $input['options'] = collect(array_map('trim', explode(',', $input['options'])))->toJson();

        unset($input['validation_args']);

        $this->merge($input);
    }
}
