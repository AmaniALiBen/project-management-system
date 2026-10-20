<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'project_id',
        'created_by',
        'assigned_to',
        'status',
        'priority',
        'due_date'
    ];
    
    public function project(){
        return $this->belongsTo(Project::class);
    }
    
    public function creator(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function assignee(){
        return $this->belongsTo(User::class,'assigned_to');
    }

    public function comments(){
        return $this->hasMany(TaskComment::class);
    }


}
