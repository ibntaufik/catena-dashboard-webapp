<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VCHPostRequest extends FormRequest
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
            "location_code"                 => "required|max:255",
            "vch_code"                      => "required|max:255",
            "email"                         => "required|email",
            "password"                      => "required|max:255",
            "latitude"                      => "required|numeric",
            "longitude"                     => "required|numeric",
            "address"                       => "required",
            "vendor_id"                     => "required|max:255",
            "vendor_name"                   => "required|max:255",
            "bank_code"                     => "required|string",
            "vendor_bank_address"           => "required|max:255",
            "vendor_bank_account_number"    => "required|max:255",
        ];
    }

    public function messages()
    {
        return [
            'location_code.required'                => 'Location is required',
            'vch_code.required'                     => 'VCH Code is required',
            'email.required'                        => 'Email is required',
            'password.required'                     => 'Password is required',
            'vendor_id.required'                    => 'Vendor ID is required',
            'vendor_name.required'                  => 'Vendor Name is required',
            'latitude.required'                     => 'Latitude is required',
            'longitude.required'                    => 'Longitude is required',
            'address.required'                      => 'Address is required',
            'bank_code.required'                    => 'Bank is required',
            'vendor_bank_account_number.required'   => 'Bank account number is required',
            'vendor_bank_address.required'          => 'Bank adrdess is required'
        ];
    }
}
