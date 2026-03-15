<?php

namespace App\Http\Requests;

use App\Enums\TaskStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateTaskStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Enforced in controller
    }

    public function rules(): array
    {
        return [
            'status' => ['required', new Enum(TaskStatusEnum::class)],
        ];
    }
}
