<?php

namespace App\Http\Requests\Sales;

use Illuminate\Foundation\Http\FormRequest;

class SaleRequest extends FormRequest
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
            'bank_id' => 'required_if:inv_type,==,2', 'nullable',
            'uncash_type' => 'required_if:inv_type,==,3', 'nullable',
            'pay_date' => ['nullable', 'required_if:inv_type,==,3', 'date', 'after:' .  Date('Y-m-d')],
            'total_price' => 'required',
            'inv_type' => 'required',
            'client_id' => 'required_if:inv_type,==,3', 'nullable',
            'total_qty' => 'required|array',
            'total_qty.*' => 'required',
            'item_qty' => 'required|array',
            'item_qty.*' => 'required'

        ];
    }

    public function messages()
    {
        return [
            'total_qty.*.required' => 'يوجد حقل خالي',
            'item_qty.*.required' => 'يوجد حقل خالي',
            'bank_id.required_if' => ':attribute مطلوب اذا كانت طريقة الدفع بالبنك',
            'uncash_type.required_if' => ':attribute مطلوب اذا كانت طريقة الدفع اجل',
            'pay_date.required_if' => ':attribute مطلوب اذا كانت طريقة الدفع اجل',
            'client_id.required_if' => ':attribute مطلوب اذا كانت طريقة الدفع اجل',
        ];
    }
}
