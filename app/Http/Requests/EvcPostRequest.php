<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EvcPostRequest extends FormRequest
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
            "code"              => "required|max:255",
            "latitude"          => "required|numeric",
            "longitude"         => "required|numeric",
            "address"           => "required",
        ];
    }

    public function messages()
    {
        return [
            'sub_district_id.required'  => 'Please select sub ditrict first',
            'sub_district_id.numeric'   => 'Sub ditrict is not valid',
            'code.required'             => 'EVC Code is required',
            'latitude.required'         => 'Latitude is required',
            'latitude.numeric'          => 'Latitude is not valid',
            'longitude.required'        => 'Longitude is required',
            'longitude.numeric'         => 'Longitude is not valid',
            'address.required'          => 'Address is required',
        ];
    }
}