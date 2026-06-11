<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'email' => [
                'nullable',
                'email',
                Rule::unique('users')->ignore($this->user()->id),
            ],
            'username' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('users')->ignore($this->user()->id),
            ],
            'birthday' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
        ];
    }
}
