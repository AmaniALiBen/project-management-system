<?php

namespace App\Models;

use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /** @use HasFactory<TaskFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'project_id',
        'created_by',
        'assigned_to',
        'status',
        'priority',
        'due_date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class);
    }

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        ];
    }

    public function scopeVisibleTo(Builder $query, User $user)
    {
        return match ($user->role) {
            'admin' => $query,
            'manager' => $query->whereHas('project', fn ($q) => $q->where('manager_id', $user->id)),
            default => $query->where('assigned_to', $user->id)
        };

    }

    
}
