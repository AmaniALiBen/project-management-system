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
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'project' => new ProjectResource($this->whenLoaded('project')),
            'creator' => new UserResource($this->whenLoaded('creator')),
            'assignee' => new UserResource($this->whenLoaded('assignee')),
            'comments' => TaskCommentResource::collection($this->whenLoaded('comments')),
            'status' => $this->status,
            'priority' => $this->priority,
            'due_date' => $this->due_date?->format('Y-m-d'),
        ];
    }
}
