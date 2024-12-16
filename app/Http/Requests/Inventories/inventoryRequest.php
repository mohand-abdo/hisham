<?php

namespace App\Http\Requests\Inventories;

use Illuminate\Foundation\Http\FormRequest;

class inventoryRequest extends FormRequest
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
            'qty' => 'required|array',
            'qty.*' => 'required|min:0',
        ];
    }

    public function messages()
    {
        return [
            'qty.*.required' => 'يوجد حقل خالي',
            'qty.*.min:0' => 'اقل قيمة ل حقل الكمية هو الصفر',
        ];
    }
}
