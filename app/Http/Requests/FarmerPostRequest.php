<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FarmerPostRequest extends FormRequest
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
            "location_code" => "required|max:255",
            "name"          => "required|max:255",
            "address"       => "required|max:255",
            "email"         => "required|email",
            "password"      => "required|max:255",
            "latitude"      => "required|numeric",
            "longitude"     => "required|numeric",
            "id_number"     => "required|max:255",
        ];
    }

    public function messages()
    {
        return [
            'location_code.required'    => 'Location is required',
            'name.required'             => 'VCP Code is required',
            'email.required'            => 'Email is required',
            'password.required'         => 'Password is required',
            'id_number.required'        => 'Identity Number is required',
            'latitude.required'         => 'Latitude is required',
            'longitude.required'        => 'Longitude is required',
            'address.required'          => 'Address is required',
        ];
    }
}
