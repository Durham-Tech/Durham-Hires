<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Classes\Common;
use App\Site;

class UpdateSite extends FormRequest
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
          'name' => 'required|max:255',
          'slug' => 'required|max:255'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(
            function ($validator) {
                $slug = str_slug($this->input(['slug']), "-");
                if ($slug == 'admin') {
                    $validator->errors()->add('email', 'This slug cannot be used.');
                }
                if (Site::where('slug', $slug)->where('id', '<>', $this->site->id)->count() > 0) {
                    $validator->errors()->add('email', 'This slug is already in use.');
                }
            }
        );
    }
}
