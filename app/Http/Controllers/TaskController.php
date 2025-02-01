<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTasksRequest;
use App\Models\Label;
use App\Models\Task;
use App\Models\TaskLabel;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
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
    public function index()
    {
        $currentUser = auth()->user();

        return view(
            'tasks.index',
            [
                'tasks' => Task::paginate(),
                'taskStatuses' => TaskStatus::all(),
                'users' => User::all(),
                'currentUser' => $currentUser,
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

        DB::transaction(function () use ($request) {
            $task = new Task(\array_merge($request->validated(), ['created_by_id' => Auth::id()]));
            $task->save();
            $task->labels()->attach(
                $request->input('labels'),
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        });

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
            $task->labels()->sync(
                $request->input('labels'),
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
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

        return redirect()->back()->with('message', $message);
    }
}
