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
            "code"          => "required|max:255",
            "district_id"   => "required|numeric",
            "name"          => "required|max:255",    
            "latitude"      => "required|numeric|max:255",
            "longitude"     => "required|numeric|max:255",
        ];
    }

    public function messages()
    {
        return [
            'code.required'         => 'ID Location is required',
            'district_id.required'  => 'Please select district first',
            'name.required'         => 'Please input sub ditrict first',
            'district_id.numeric'   => 'District is not valid',
            'latitude.required'     => 'Latitude is required',
            'longitude.required'    => 'Longitude is required',
        ];
    }
}
