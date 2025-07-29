<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTasksRequest;
use App\Models\Label;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use AppSite\Infrastructure\CreateTask;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    private function ensureAuthorized()
    {
        if (!Auth::check()) {
            throw new AuthorizationException();
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $currentUser = auth()->user();
        $queryTask = Task::query();
        $formFilter = [
            'statusId' => $request->integer('filter.status_id', 0),
            'createdById' => $request->integer('filter.created_by_id', 0),
            'assignedToId' => $request->integer('filter.assigned_to_id', 0),
        ];

        if ($formFilter['statusId'] > 0) {
            $queryTask->where('status_id', $formFilter['statusId']);
        }

        if ($formFilter['createdById'] > 0) {
            $queryTask->where('created_by_id', $formFilter['createdById']);
        }

        if ($formFilter['assignedToId'] > 0) {
            $queryTask->where('assigned_to_id', $formFilter['assignedToId']);
        }

        return view(
            'tasks.index',
            [
                'tasks' => $queryTask->paginate(),
                'taskStatuses' => TaskStatus::all(),
                'users' => User::all(),
                'currentUser' => $currentUser,
                'formFilter' => $formFilter,
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->ensureAuthorized();

        return view(
            'tasks.create',
            [
                'taskStatuses' => TaskStatus::all(),
                'users' => User::all(),
                'labels' => Label::all(),
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTasksRequest $request)
    {
        $this->ensureAuthorized();

        DB::transaction(fn() => (new CreateTask($request))->execute());

        return redirect()->route('tasks.index')->with('messageTask', trans('task_manager.messagesTask.createSuccess'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return view(
            'tasks.show',
            [
                'task' => $task,
                'taskLabels' => $task->labels->toArray(),
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $this->ensureAuthorized();

        return view(
            'tasks.edit',
            [
                'task' => $task,
                'taskStatuses' => TaskStatus::all(),
                'users' => User::all(),
                'labels' => Label::all(),
                'taskLabels' => $task->labels->pluck('id')->toArray(),
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreTasksRequest $request, Task $task)
    {
        $this->ensureAuthorized();

        DB::transaction(function () use ($request, $task) {
            $task->update($request->validated());
            $task->labels()->sync($request->input('labels'));
        });

        return redirect()->route('tasks.index')->with('messageTask', trans('task_manager.messagesTask.updateSuccess'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $this->ensureAuthorized();

        if (Auth::id() === $task->created_by_id) {
            $task->delete();
            $task->labels()->detach();
            $message = trans('task_manager.messagesTask.removedSuccess');
        } else {
            $message = trans('task_manager.messagesTask.removedError');
        }

        return redirect()->route('tasks.index')->with('messageTask', $message);
    }
}
