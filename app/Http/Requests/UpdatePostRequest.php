<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
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
            "title" => "required|string|max:255",
            "body" => "required|array|max:255",
            "body.*" => "required|string|max:255",
            "user_ids" => "required|array|exists:users,id",
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, mixed>
     */
    public function messages()
    {
        return [
            "title.required" => "The title is required.",
            "title.string" => "The title must be a string.",
            "title.max" => "The title must be less than 255 characters.",
            "body.required" => "The body is required.",
            "body.array" => "The body must be an array.",
            "body.max" => "The body must be less than 255 characters.",
            "body.*.required" => "The body is required.",
            "body.*.string" => "The body must be a string.",
            "body.*.max" => "The body must be less than 255 characters.",
            "user_ids.required" => "The user ids is required.",
            "user_ids.array" => "The user ids must be an array.",
            "user_ids.exists" => "The user ids must be an array of existing user ids.",
        ];
    }
}
