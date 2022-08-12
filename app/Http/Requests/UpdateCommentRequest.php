<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCommentRequest extends FormRequest
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
            "body" => "required|string|max:255",
            "user_id" => "required|integer|exists:users,id",
            "post_id" => "required|integer|exists:posts,id",
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
            "body.required" => "The body is required.",
            "user_id.required" => "The user id is required.",
            "user_id.integer" => "The user id must be an integer.",
            "user_id.exists" => "The user id must exist in the users table.",
            "post_id.required" => "The post id is required.",
            "post_id.integer" => "The post id must be an integer.",
            "post_id.exists" => "The post id must exist in the posts table.",
        ];
    }
}
