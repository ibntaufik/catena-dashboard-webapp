<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserPostRequest extends FormRequest
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
            "email"     => "required|email",
            "name"      => "required|max:255",
            "password"  => "required",
            "role_at"   => "required"
        ];
    }

    public function messages()
    {
        return [
            'email.required'    => 'Email is required',
            'name.required'     => 'Name is required',
            'password.required' => 'Password is required',
            'role_at.required'  => 'Role is required'
        ];
    }
}
