<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskStatus;
use App\Models\TaskStatus;
use Illuminate\Auth\Access\AuthorizationException;

class TaskStatusController extends Controller
{
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
        if (auth()->guest()) {
            throw new AuthorizationException();
        }

        return view('task_status.create');
    }

    /**
     * Store a newly created resource in storage. Сохранить новый статус из формы создания статуса.
     */
    public function store(StoreTaskStatus $request)
    {
        $taskStatus = new TaskStatus();
        $taskStatus->name = $request->input('name');
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
        if (auth()->guest()) {
            throw new AuthorizationException();
        }

        return view('task_status.edit', compact('taskStatus'));
    }

    /**
     * Update the specified resource in storage. Обновить указанный статус из формы обновления статуса.
     */
    public function update(StoreTaskStatus $request, TaskStatus $taskStatus)
    {
        if (auth()->guest()) {
            throw new AuthorizationException();
        }

        $taskStatus->name = $request->input('name');
        $taskStatus->update();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage. Удалить указанный статус.
     */
    public function destroy(TaskStatus $taskStatus)
    {
        if (auth()->guest()) {
            throw new AuthorizationException();
        }

        $taskStatus->delete();

        return redirect()->route('task_statuses.index')->with('status', trans('task_manager.messages.removedSuccess'));
    }
}
