<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskStatus;
use App\Models\Task;
use App\Models\TaskStatus;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

class TaskStatusController extends Controller
{
    private function ensureAuthorized()
    {
        if (!Auth::check()) {
            throw new AuthorizationException();
        }
    }

    /**
     * Display a listing of the resource. Список статусов
     */
    public function index()
    {
        return view('task_status.index', ['statuses' => TaskStatus::all()]);
    }

    /**
     * Show the form for creating a new resource. Форма создания статуса.
     */
    public function create()
    {
        $this->ensureAuthorized();

        return view('task_status.create');
    }

    /**
     * Store a newly created resource in storage. Сохранить новый статус из формы создания статуса.
     */
    public function store(StoreTaskStatus $request)
    {
        $this->ensureAuthorized();

        $taskStatus = new TaskStatus($request->validated());
        $taskStatus->save();

        return redirect()->route('task_statuses.index')->with('status', trans('task_manager.messages.createSuccess'));
    }

    /**
     * Display the specified resource. Показать данные по статусу.
     */
    public function show(TaskStatus $taskStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource. Показать форму редактирования статуса.
     */
    public function edit(TaskStatus $taskStatus)
    {
        $this->ensureAuthorized();

        return view('task_status.edit', compact('taskStatus'));
    }

    /**
     * Update the specified resource in storage. Обновить указанный статус из формы обновления статуса.
     */
    public function update(StoreTaskStatus $request, TaskStatus $taskStatus)
    {
        $this->ensureAuthorized();

        $taskStatus->update($request->validated());

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage. Удалить указанный статус.
     */
    public function destroy(TaskStatus $taskStatus)
    {
        $this->ensureAuthorized();

        $tasksCount = Task::query()->where('status_id', $taskStatus->id)->select('id')->count();

        if ($tasksCount === 0) {
            $taskStatus->delete();
            $message = trans('task_manager.messages.removedSuccess');
        } else {
            $message = trans('task_manager.messages.removedError');
        }

        return redirect()->route('task_statuses.index')->with('status', $message);
    }
}
