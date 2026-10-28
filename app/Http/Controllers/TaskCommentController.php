<?php

namespace App\Http\Controllers;

use App\Models\TaskComment;
use App\Http\Requests\StoreTaskCommentRequest;
use App\Http\Requests\UpdateTaskCommentRequest;
use App\Http\Resources\TaskCommentResource;
use App\Models\Task;
use Illuminate\Support\Facades\Gate;

class TaskCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Task $task)
    {
        Gate::authorize('view',$task);
        $comments=$task->comments()->with('user')->latest()->paginate(10);
        return TaskCommentResource::collection($comments);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskCommentRequest $request,Task $task)
    {
         Gate::authorize('view',$task);
         $data=$request->validated();
         $data['user_id']=$request->user()->id;
         $data['task_id']=$task->id;
         $comment=TaskComment::create($data);
         return TaskCommentResource::make($comment->load('user'))->response()->setStatusCode(201);

    }

    /**
     * Display the specified resource.
     */
    public function show(TaskComment $taskComment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskCommentRequest $request, TaskComment $taskComment)
    {
        Gate::authorize('update',$taskComment);
        $taskComment->update($request->validated());
        return TaskCommentResource::make($taskComment->load('user'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaskComment $taskComment)
    {
        Gate::authorize('delete',$taskComment);
        $taskComment->delete();
        return response()->noContent();
    }
}
