<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserCreateRequest extends FormRequest
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
            'first_name' => 'required|max:255',
            'last_name'  => 'required|max:255',
            'username'   => [
                'required',
                'max:255',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('tenant_id', auth()->user()->tenant_id);
                }),

            ],
            'email'      => [
                'required',
                'max:255',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('tenant_id', auth()->user()->tenant_id);
                }),
            ],
            'password'   => [
                'bail',
                'required',
                'confirmed',
                'min:6',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/',
            ],
            'role_id'    => 'required',
            'active'     => 'required',
        ];
    }

    public function messages()
    {
        return [
            'password.regex' => 'The Password must contain at least 1 upper case letter, 1 lower case letter, a number, and a symbol.',
        ];
    }
}
