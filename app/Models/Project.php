<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'manager_id',
        'status',
        'start_date',
        'due_date'
    ];
   
    public function manager(){
        return $this->belongsTo(User::class,'manager_id');
    }

    public function members(){
        return $this->belongsToMany(User::class);
    }

    public function tasks(){
        return $this->hasMany(Task::class);
    }

    protected function casts():array{
        return[
            'start_date'=>'date',
            'due_date'=>'date'
        ];
    }
}
