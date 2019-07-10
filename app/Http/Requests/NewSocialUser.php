<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Classes\Common;
use App\Admin;
use App\Site;

class NewSocialUser extends FormRequest
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
          'name' => 'required',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(
            function ($validator) {
                $site = $this->site;
                if (is_null($site)) {
                    $id = 0;
                }
                else if (gettype($site) == "string") {
                    $id = Site::where('slug', $site)->first()->id;
                } else {
                    $id = $site->id;
                }
                if (Admin::where('email', $this->input(['email']))->where('site', $id)->count() != 0) {
                    $validator->errors()->add('email', 'The user already exists.');
                }
            }
        );
    }
}
