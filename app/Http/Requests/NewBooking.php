<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Classes\CAuth;
use App\Classes\Common;

class NewBooking extends FormRequest
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
            //
            'name' => 'required',
            'start' => 'required|date',
            'end' => 'required|date|after:start',
            // 'end' => 'required|date|after_or_equal:start',
            'discDays' => 'integer|min:0',
            'discType' => 'integer|min:0|max:1',
            'discValue' => 'numeric|min:0',
            'fineValue' => 'numeric|min:0',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (CAuth::checkAdmin(4) && !Common::getDetailsEmail($this->input(['email']))) {
                $validator->errors()->add('email', 'The email is not a valid durham email address.');
            }
        });
    }
}
