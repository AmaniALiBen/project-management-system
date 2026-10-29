<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;

class TaskCommentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user, Task $task): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TaskComment $taskComment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TaskComment $comment): bool
    {
        return ($user->id === $comment->user_id) && ($user->can('view', $comment->task));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TaskComment $comment): bool
    {
        return $user->role === 'admin'
        || ($user->id === $comment->user_id && $user->can('view', $comment->task))
        || ($user->role === 'manager' && $user->id === $comment->task->project?->manager_id);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TaskComment $taskComment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TaskComment $taskComment): bool
    {
        return false;
    }
}
