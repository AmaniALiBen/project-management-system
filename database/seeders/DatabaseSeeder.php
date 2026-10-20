<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'role' => 'admin'
        ]);

        $managers=User::factory(5)->create(['role'=>'manager']);
        $employees=User::factory(10)->create(['role'=>'employee']);

        $projects=Project::factory(5)->recycle($managers)->create();

        foreach($projects as $project){
            $members=$employees->random(random_int(1,6));
            $project->members()->attach($members);

            $tasks=Task::factory(random_int(2,5))->create([
                'project_id'=> $project->id,
                'created_by'=>$project->manager_id,
                'assigned_to'=> fn()=>$members->random()->id,
            ]);
            foreach($tasks as $task){
                TaskComment::factory(random_int(1,6))->create([
                     'task_id'=> $task->id,
                     'user_id'=>fn()=>$members->random()->id
                     ]);
            }

        }
    }
}
