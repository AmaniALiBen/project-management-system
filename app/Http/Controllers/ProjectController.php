<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddMemberRequest;
use App\Http\Requests\IndexProjectRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexProjectRequest $request)
    {
        Gate::authorize('viewAny', Project::class);
        $projects = Project::visibleTo($request->user())
        ->when($request->search,fn($q,$search)=>$q->where('name','like',"%{$search}%"))
        ->when($request->status,fn($q,$status)=>$q->where('status',$status))
        ->with('manager')
        ->paginate($request->integer('per_page',10))
        ->withQueryString();

        return ProjectResource::collection($projects);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        Gate::authorize('create', Project::class);

        $data = $request->validated();

        if ($request->user()->role === 'manager') {
            $data['manager_id'] = $request->user()->id;
        }

        $project = Project::create($data);

        return ProjectResource::make($project->load('manager'))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project,Request $request)
    {
        Gate::authorize('view', $project);

        $project->load([
            'manager',
            'members',
            'tasks'=>fn($q)=>$q->VisibleTo($request->user())
            ]);

        return ProjectResource::make($project);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        Gate::authorize('update', $project);
        $project->update($request->validated());

        return ProjectResource::make($project->load('manager'));

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Project $project)
    {
         Gate::authorize('delete', $project);
        if ($request->user()->role === 'admin') {
            Gate::authorize('forceDelete', $project);
            $project->forceDelete();
        }
        else if($request->user()->role === 'manager') {
            $project->delete();
        }

        return response()->noContent();
    } 

    public function addMember(AddMemberRequest $request, Project $project)
    {
        Gate::authorize('manageMembers', $project);

        $project->members()->syncWithoutDetaching([$request->validated()['user_id']]);

        return ProjectResource::make($project->load('manager', 'members'))->response()->setStatusCode(201);

    }

    public function removeMember(Project $project, User $user)
    {
        Gate::authorize('manageMembers', $project);

        $project->members()->detach($user->id);

        return response()->noContent();
    }
}
