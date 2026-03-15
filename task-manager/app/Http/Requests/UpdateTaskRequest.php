<?php

namespace App\Http\Requests;

use App\Enums\TaskPriorityEnum;
use App\Enums\TaskStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Enforced in controller via policy
    }

    public function rules(): array
    {
        return [
            'title'       => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority'    => ['sometimes', 'required', new Enum(TaskPriorityEnum::class)],
            'status'      => ['sometimes', 'required', new Enum(TaskStatusEnum::class)],
            'due_date'    => ['sometimes', 'required', 'date'],
            'assigned_to' => ['sometimes', 'required', 'exists:users,id'],
        ];
    }
}
