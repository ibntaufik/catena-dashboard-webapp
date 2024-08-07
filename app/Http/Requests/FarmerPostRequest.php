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
            "sub_district_id"   => "required|numeric",
            "name"              => "required|max:255",
            "address"           => "required|max:255",
            "latitude"          => "required|numeric",
            "longitude"         => "required|numeric",
            "id_number"         => "required|max:255",
        ];
    }

    public function messages()
    {
        return [
            'sub_district_id.required'  => 'Please select sub ditrict first',
            'sub_district_id.numeric'   => 'Sub ditrict is not valid',
            'name.required'             => 'VCP Code is required',
            'id_number.required'        => 'Identity Number is required',
            'latitude.required'         => 'Latitude is required',
            'longitude.required'        => 'Longitude is required',
            'address.required'          => 'Address is required',
        ];
    }
}
