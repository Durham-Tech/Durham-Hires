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
            'discDays' => 'integer|min:0',
            'discType' => 'integer|min:0|max:1',
            'discValue' => 'numeric|min:0',
            'fineValue' => 'numeric|min:0',
            'status' => 'integer|min:0',
            'VAT' => 'boolean|min:0',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(
            function ($validator) {

                // Mailgun nolonger offer a free email validation stream_resolve_include_path

                // if (CAuth::checkAdmin(4) && $this->input(['email']) != '') {
                //     $query = http_build_query(
                //         [
                //         'address' => $this->input(['email'])
                //         ]
                //     );
                //     $remote_url = 'https://api.mailgun.net/v3/address/validate?' . $query;
                //
                //     $ch = curl_init();
                //     curl_setopt($ch, CURLOPT_URL, $remote_url);
                //     curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
                //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                //     curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                //     curl_setopt($ch, CURLOPT_USERPWD, "api:pubkey-95c8fd3b3467e6982561e7c22c3b9dc7");
                //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                //     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
                //
                //     $result=json_decode(curl_exec($ch));
                //     curl_close($ch);
                //
                //
                //     if (!($result->is_valid)) {
                //         if ($result->did_you_mean) {
                //             $validator->errors()->add('email', 'The provided email address is not valid. Did you mean ' . $result->did_you_mean);
                //         } else {
                //             $validator->errors()->add('email', 'The provided email address is not valid.');
                //         }
                //     }
                // }
            }
        );
    }
}
