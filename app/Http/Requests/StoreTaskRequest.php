<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'is_routine' => 'boolean',
            'description' => 'nullable|string',
            'coin_reward' => 'nullable|integer|min:0',
            'task_type' => 'nullable|string|max:100',
        ];
    }
}
