<?php

namespace App\Http\Requests;

use App\Enums\TaskPriorityEnum;
use App\Enums\TaskStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Authorization handled implicitly by TaskPolicy via controller
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority'    => ['required', new Enum(TaskPriorityEnum::class)],
            'status'      => ['required', new Enum(TaskStatusEnum::class)],
            'due_date'    => ['required', 'date'],
            'assigned_to' => ['required', 'exists:users,id'],
        ];
    }
}
