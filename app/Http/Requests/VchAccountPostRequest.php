<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VchAccountPostRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "vch_code"                      => "required|max:255",
            "account_code"                  => "required|max:255",
            "bank_code"                     => "required|string",
            "vendor_bank_address"           => "required|max:255",
            "vendor_bank_account_number"    => "required|max:255",
        ];
    }

    public function messages()
    {
        return [
            'vch_code.required'                     => 'VCH Code is required',
            'account_code.required'                 => 'Account Code is required',
            'bank_code.required'                    => 'Bank is required',
            'vendor_bank_account_number.required'   => 'Bank account number is required',
            'vendor_bank_address.required'          => 'Bank adrdess is required'
        ];
    }
}
