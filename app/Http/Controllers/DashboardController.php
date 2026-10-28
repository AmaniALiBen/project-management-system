<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function statistics(Request $request)
    {
        $user=$request->user();
        $projectsTotal=Project::visibleTo($user)->count();
        $activeProjects=Project::visibleTo($user)->where('status','active')->count();
        $completedProjects=Project::visibleTo($user)->where('status','completed')->count();
        $tasksTotal=Task::visibleTo($user)->count();
        $toDoTasks=Task::visibleTo($user)->where('status','to_do')->count();
        $inProgressTasks=Task::visibleTo($user)->where('status','in_progress')->count();
        $inReviewTasks=Task::visibleTo($user)->where('status','in_review')->count();
        $doneTasks=Task::visibleTo($user)->where('status','done')->count();
        $overdueTasks=Task::visibleTo($user)->where('status','!=','done')->where('due_date','<',now())->count();

        return response()->json([
            'projects'=>[
                'total'=>$projectsTotal,
                'active'=>$activeProjects,
                'completed'=>$completedProjects
            ],
            'tasks'=>[
                'total'=>$tasksTotal,
                'to_do'=>$toDoTasks,
                'in_progress'=>$inProgressTasks,
                'in_review'=>$inReviewTasks,
                'done'=>$doneTasks,
                'overdue'=>$overdueTasks
            ],
        ]);

    }
}
