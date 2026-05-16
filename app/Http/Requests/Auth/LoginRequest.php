<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'     => ['required', 'string', 'email', 'max:255'],
            'password'  => ['required', 'string', 'min:8', 'max:255'],
            'remember'  => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'    => 'Please enter your email address.',
            'email.string'      => 'Email address must be valid text.',
            'email.email'       => 'Please enter a valid email address.',
            'email.max'         => 'Email address cannot exceed 255 characters.',

            'password.required' => 'Please enter your password.',
            'password.string'   => 'Password must be valid text.',
            'password.min'      => 'Password must be at least 8 characters.',
            'password.max'      => 'Password cannot exceed 255 characters.',
            'remember.boolean'  => 'Remember me must be yes or no.',
        ];
    }

    public function attributes(): array
    {
        return [
            'email'     => 'email address',
            'password'  => 'password',
            'remember'  => 'remember me',
        ];
    }
}
