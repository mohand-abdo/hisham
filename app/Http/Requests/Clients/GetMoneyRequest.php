<?php

namespace App\Http\Requests\Clients;

use Illuminate\Foundation\Http\FormRequest;

class GetMoneyRequest extends FormRequest
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
            'price' => 'required',
            'bank' => 'required_if:inv_type,==,2', 'nullable',
            'safe' => 'required_if:inv_type,==,1', 'nullable',
        ];
    }

    public function messages(){
        return[
            'bank.required_if' => ':attribute مطلوب اذا كانت طريقة الدفع بالبنك',
            'safe.required_if' => ':attribute مطلوب اذا كانت طريقة الدفع كاش',
        ];
    }
}
