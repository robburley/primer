<?php

namespace App\Http\Requests\Campaigns;

use Illuminate\Foundation\Http\FormRequest;

class CampaignRequest extends FormRequest
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
        $rules = collect([
            'name'          => 'required|max:255',
            'active'        => 'required',
            'endpoint_type' => 'required',
        ]);

        if ($this->get('endpoint_type') == 1) {
            $rules->put('endpoint_location', 'required|url');
        } elseif ($this->get('endpoint_type') == 2) {
            $rules->put('endpoint_location', 'required|email');
        }

        return $rules->toArray();
    }
}
