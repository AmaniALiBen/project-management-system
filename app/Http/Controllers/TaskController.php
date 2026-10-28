<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexTaskRequest;
use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Requests\UpdateTaskStatusRequest;
use App\Http\Resources\TaskResource;
use App\Models\Project;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexTaskRequest $request)
    {
        Gate::authorize('viewAny',Task::class);
        $tasks=Task::visibleTo($request->user())
        ->when($request->search,fn($q,$search)=>$q->where('title','like',"%{$search}%"))
        ->when($request->status,fn($q,$status)=>$q->where('status',$status))
        ->when($request->priority,fn($q,$priority)=>$q->where('priority',$priority))
        ->when($request->assigned_to,fn($q,$assignedTo)=>$q->where('assigned_to',$assignedTo))
        ->when($request->due_date,fn($q,$dueDate)=>$q->whereDate('due_date',$dueDate))
        ->with('project','assignee','creator')
        ->paginate($request->integer('per_page',10))
        ->withQueryString();

        return TaskResource::collection($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $project=Project::findOrFail($request->validated()['project_id']);
        Gate::authorize('create',[Task::class,$project]);
        $data=$request->validated();
        $data['created_by']=$request->user()->id;
        $task=Task::create($data);
        return TaskResource::make($task->load('project','creator','assignee'))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        Gate::authorize('view',$task);
        return TaskResource::make($task->load('project','creator','assignee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        Gate::authorize('update',$task);
        $task->update($request->validated());
        return TaskResource::make($task->load('project','creator','assignee'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        Gate::authorize('delete',$task);
        $task->delete();
        return response()->noContent();
    }

    public function updateStatus(UpdateTaskStatusRequest $request,Task $task){
        Gate::authorize('updateStatus',$task);
        $task->update($request->validated());
        return TaskResource::make($task->load('project','creator','assignee'));
    }

    public function projectTasks(IndexTaskRequest $request,Project $project){
        Gate::authorize('view',$project);
        $tasks=Task::visibleTo($request->user())
        ->where('project_id',$project->id)
        ->when($request->search,fn($q,$search)=>$q->where('title','like',"%{$search}%"))
        ->when($request->status,fn($q,$status)=>$q->where('status',$status))
        ->when($request->priority,fn($q,$priority)=>$q->where('priority',$priority))
        ->when($request->assigned_to,fn($q,$assignedTo)=>$q->where('assigned_to',$assignedTo))
        ->when($request->due_date,fn($q,$dueDate)=>$q->whereDate('due_date',$dueDate))
        ->with('assignee','creator')
        ->paginate($request->integer('per_page',10))
        ->withQueryString();

        return TaskResource::collection($tasks);
    }
}
