<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocationPostRequest extends FormRequest
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
            "code"   => "required|max:255",
            "sub_district"  => "required|max:255",
            "district"      => "required|max:255",
            "city"          => "required|max:255",
            "province"      => "required|max:255",
            "latitude"      => "required|numeric|max:255",
            "longitude"     => "required|numeric|max:255",
        ];
    }

    public function messages()
    {
        return [
            'code.required'  => 'ID Location is required',
            'sub_district.required' => 'Desa is required',
            'district.required'      => 'Kecamatan is required',
            'city.required'         => 'Kabupaten is required',
            'province.required'     => 'Provinsi is required',
            'latitude.required'     => 'Latitude is required',
            'longitude.required'    => 'Longitude is required',
        ];
    }
}
