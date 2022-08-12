<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            "name" => "required|string|max:255",
            "email" => "required|string|email|max:255|unique:users,email," . $this->user->id,
            "password" => "nullable|string|min:6|confirmed",
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            "name.required" => "The name is required.",
            "email.required" => "The email is required.",
            "email.email" => "The email must be a valid email address.",
            "email.max" => "The email must be less than 255 characters.",
            "email.unique" => "The email must be unique.",
            "password.required" => "The password is required.",
            "password.min" => "The password must be at least 6 characters.",
            "password.confirmed" => "The password confirmation does not match.",
        ];
    }
}
