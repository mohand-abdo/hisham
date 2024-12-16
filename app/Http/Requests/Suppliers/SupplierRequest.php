<?php

namespace App\Http\Requests\Suppliers;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'phone' => 'required|array|min:1',
            'phone.0' => 'required_without:phone.1',
            'address' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'phone.0.required_without' => __('dashboard.check_phone')
        ];
    }
}
