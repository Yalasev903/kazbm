<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SiteUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        $rules = [
            'name' => 'required|string',
            'phone' => 'required|string',
            'password' => 'required|confirmed|min:6',
            'email' => 'required|email|unique:site_users,email',
        ];

        if ($user = auth()->guard('site')->user()) {
            $rules['email'] .= ','. $user->id;
        }

        return $rules;
    }
}
