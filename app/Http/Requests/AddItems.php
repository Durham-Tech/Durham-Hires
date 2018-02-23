<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class addItems extends FormRequest
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
          'quantity.*' => 'required_with:description.*,price.*|nullable|integer|min:0',
          'price.*' => 'required_with:description.*,quantity.*|nullable|numeric|min:0',
          'description.*' => 'required_with:price.*,quantity.*',
        ];
    }

    public function messages()
    {
        return [
          'quantity.*' => 'A custom item quantity is missing or invalid',
          'price.*' => 'A custom item price is missing or invalid',
          'description.*' => 'A custom item description is missing',
        ];
    }
}
