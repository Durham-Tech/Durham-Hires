<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewPatTest extends FormRequest
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
          'id' => array(
                    'required',
                    'regex:/^[A-Z1-9][A-Z0-9]*$/',
                    'max:255'
                  ),
          'description' => 'required|max:255',
          'date' => 'required|date',
          'fuse' => 'nullable|integer',
          'cable_length' => 'nullable|numeric',
          'test_current' => 'nullable|numeric',
        ];
    }
}
