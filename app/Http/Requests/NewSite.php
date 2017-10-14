<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Classes\Common;
use App\Site;

class NewSite extends FormRequest
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
          'email' => 'required|email|max:255',
          'name' => 'required|max:255',
          'slug' => 'required|max:255'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(
            function ($validator) {
                if (!Common::getDetailsEmail($this->input(['email']))) {
                    $validator->errors()->add('email', 'The email is not a valid durham email address.');
                }
                $slug = str_slug($this->input(['slug']), "-");
                if ($slug == 'admin') {
                    $validator->errors()->add('email', 'This slug cannot be used.');
                }
                if (Site::where('slug', $slug)->count() > 0) {
                    $validator->errors()->add('email', 'This slug is already in use.');
                }
            }
        );
    }
}
