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
        // Get current row id for uniqueness check on update
        if (count($this->discount)) {
            $id = $this->discount->id;
        } else {
            $id = 0;
        }

        return [
        'discType' => 'integer|min:0|max:1',
        'discValue' => 'numeric|min:0',
        'code' => [
        'required',
        Rule::unique('discount_codes', 'code')->ignore($id, 'id')->where(
            function ($query) use (&$id) {
                $query->where('site', Request()->get('_site')->id);
            }
        ),
          ]
        ];
    }

    public function messages()
    {
        return [
        'code.unique' => 'This code is already in use.',
        'discValue.numeric' => 'The discount value is invalid.',
        'discValue.min' => 'The discount value cannot be negative.',
        ];
    }
}
