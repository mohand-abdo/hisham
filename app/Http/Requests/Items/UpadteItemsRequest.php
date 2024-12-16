<?php

namespace App\Http\Requests\Items;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpadteItemsRequest extends FormRequest
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
            'name' => ['required', 'min:3', Rule::unique('items', 'name')->ignore($this->item->id, 'id')],
            'category_id' => 'required',
            'purches_price' => 'required|numeric|min:1',
            'sale_price' => 'required||numeric|gt:purches_price',
            // 'qty' => 'required|numeric|min:0'
        ];
    }
}
