<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'string', 'min:8'],
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'name.string' => 'Name must be a string',
            'name.max' => 'Name must not be greater than 255 characters',
            'email.required' => 'Email is required',
            'email.string' => 'Email must be a string',
            'email.lowercase' => 'Email must be lowercase',
            'email.email' => 'Email must be a valid email',
            'email.max' => 'Email must not be greater than 255 characters',
            'email.unique' => 'Email must be unique',
            'password.required' => 'Password is required',
            'password.confirmed' => 'Password must be confirmed',
            'password.string' => 'Password must be a string',
            'password.min' => 'Password must not be less than 8 characters',
        ];
    }
}
