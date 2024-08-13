<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VcpAccountPostRequest extends FormRequest
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
            "vcp_code"      => "required",
            "account_code"  => "required",
        ];
    }

    public function messages()
    {
        return [
            'vcp_code.required'     => 'VCP Code is required',
            'account_code.required' => 'Account Code is required',
        ];
    }
}
