<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Classes\Common;
use App\Admin;

class newUser extends FormRequest
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
          'email' => 'required|email',
        ];
    }

    public function withValidator($validator)
    {
      $validator->after(function ($validator) {
        if (!Common::getDetailsEmail($this->input(['email']))) {
            $validator->errors()->add('email', 'The email is not a valid durham email address.');
        }
        if (Admin::where('email', $this->input(['email']))->count() != 0){
          echo 'smith';
            $validator->errors()->add('email', 'The user already exists.');
        }
    });
    }
}
