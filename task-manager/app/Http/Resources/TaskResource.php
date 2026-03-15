<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'priority'    => $this->priority->value,
            'status'      => $this->status->value,
            'due_date'    => $this->due_date?->format('Y-m-d'),
            // Optionally embed the assigned user if loaded
            'assigned_user' => $this->whenLoaded('assignedUser', function () {
                return [
                    'id'    => $this->assignedUser->id,
                    'name'  => $this->assignedUser->name,
                    'email' => $this->assignedUser->email,
                ];
            }),
            'ai_summary'  => $this->ai_summary,
            'ai_priority' => $this->ai_priority?->value,
            'created_at'  => $this->created_at?->toDateTimeString(),
            'updated_at'  => $this->updated_at?->toDateTimeString(),
        ];
    }
}
