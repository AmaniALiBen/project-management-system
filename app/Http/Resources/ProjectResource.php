<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'manager' => new UserResource($this->whenLoaded('manager')),
            'status' => $this->status,
            'start_date' => $this->start_date->format('Y-m-d'),
            'due_date' => $this->due_date?->format('Y-m-d'),
            'members' => UserResource::collection($this->whenLoaded('members')),
            'tasks' => TaskResource::collection($this->whenLoaded('tasks')),
        ];
    }
}
