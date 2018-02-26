<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NewDiscount extends FormRequest
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
        'discType' => 'integer|min:0|max:1',
        'discValue' => 'numeric|min:0',
        'code' => [
        'required',
        Rule::unique('discountcodes', 'code')->where(
            function ($query) {
                $query->where('site', Request()->get('_site')->id);
            }
        ),
          ]
        ];
    }

    public function messages()
    {
        return [
        'discountCode.unique' => 'This code is already in use.',
        'discValue.numeric' => 'The discount value is invalid.',
        'discValue.min' => 'The discount value cannot be negative.',
        ];
    }
}
