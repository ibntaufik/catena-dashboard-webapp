<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VCPPostRequest extends FormRequest
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
            "sub_district_id"           => "required|numeric",
            "vcp_code"                  => "required|max:255",
            "field_coordinator_id"      => "required|max:255",
            "field_coordinator_name"    => "required|max:255",
            "latitude"                  => "required|numeric",
            "longitude"                 => "required|numeric",
            "address"                   => "required",
            "password"                  => "required|max:255",
            "email"                     => "required|email",
        ];
    }

    public function messages()
    {
        return [
            'sub_district_id.required'          => 'Please select sub ditrict first',
            'sub_district_id.numeric'           => 'Sub ditrict is not valid',
            'vcp_code.required'                 => 'VCP Code is required',
            'field_coordinator_id.required'     => 'Field Coordination ID is required',
            'field_coordinator_name.required'   => 'Field Coordination Name is required',
            'latitude.required'                 => 'Latitude is required',
            'longitude.required'                => 'Longitude is required',
            'address.required'                  => 'Address is required',
            'email.required'                    => 'Email is required',
            'password.required'                 => 'Password is required',
        ];
    }
}
