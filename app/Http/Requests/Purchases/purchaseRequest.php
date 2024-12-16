<?php

namespace App\Http\Requests\Purchases;

use Illuminate\Foundation\Http\FormRequest;

class purchaseRequest extends FormRequest
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
            'inv_date' => ['nullable', 'date', 'before_or_equal:' .  Date('Y-m-d')],
            'inv_no' => 'nullable',
            'bank_id' => 'required_if:inv_type,==,2', 'nullable',
            'safe' => 'required_if:inv_type,==,1', 'nullable',
            // 'uncash_type' => 'required_if:inv_type,==,3','nullable',
            'pay_date' => ['nullable', 'required_if:inv_type,==,3', 'date', 'after:' .  Date('Y-m-d')],
            'total_price' => ['required'],
            'inv_type' => 'required',
            'supplier_id' => 'required_if:inv_type,==,3', 'nullable',
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
            'safe.required_if' => ':attribute مطلوب اذا كانت طريقة الدفع كاش',
            // 'uncash_type.required_if' => ':attribute مطلوب اذا كانت طريقة الدفع اجل',
            'pay_date.required_if' => ':attribute مطلوب اذا كانت طريقة الدفع اجل',
            'supplier_id.required_if' => ':attribute مطلوب اذا كانت طريقة الدفع اجل',
        ];
    }
}
