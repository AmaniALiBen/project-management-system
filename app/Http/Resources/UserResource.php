<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'role' => $this->role,
            'managed_projects' => ProjectResource::collection($this->whenLoaded('managedProjects')),
            'projects' => ProjectResource::collection($this->whenLoaded('projects')),
            'task_comments' => TaskCommentResource::collection($this->whenLoaded('taskComments')),
            'assigned_tasks' => TaskResource::collection($this->whenLoaded('assignedTasks')),
            'created_tasks' => TaskResource::collection($this->whenLoaded('createdTasks')),
        ];
    }
}
