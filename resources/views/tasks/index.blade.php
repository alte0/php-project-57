@extends('layouts.tasks')

@section('content')
    @if (session('messageTask'))
        <div class="alert alert-success">
            {{ session('messageTask') }}
        </div>
    @endif
    <div class="grid col-span-full">
        <h1 class="mb-5">@lang('task_manager.tasks')</h1>
        <div class="w-full flex items-center">
            <div>
                <form method="GET" action="">
                    <div class="flex">
                        <select
                            class="rounded border-gray-300"
                            name="filter[status_id]"
                            id="filter[status_id]"
                        >
                            <option value="" selected>@lang('task_manager.status')</option>
                            @foreach($taskStatuses as $taskStatus)
                            <option value="{{ $taskStatus->id }}">{{ $taskStatus->name }}</option>
                            @endforeach
                        </select>
                        <select
                            class="rounded border-gray-300"
                            name="filter[created_by_id]"
                            id="filter[created_by_id]"
                        >
                            <option value="" selected>@lang('task_manager.author')</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <select
                            class="rounded border-gray-300"
                            name="filter[assigned_to_id]"
                            id="filter[assigned_to_id]"
                        >
                            <option value="" selected>@lang('task_manager.executor')</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach</select>
                        <button
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2"
                            type="submit"
                        >@lang('task_manager.apply')</button>
                    </div>
                </form>
            </div>
            <div class="ml-auto">
                @auth
                <a
                    href="{{ route('tasks.create') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2"
                >@lang('task_manager.toCreateTask')</a>
                @endauth
            </div>
        </div>
        <table class="mt-4">
            <thead class="border-b-2 border-solid border-black text-left">
                <tr>
                    <th>ID</th>
                    <th>@lang('task_manager.status')</th>
                    <th>@lang('task_manager.name')</th>
                    <th>@lang('task_manager.author')</th>
                    <th>@lang('task_manager.executor')</th>
                    <th>@lang('task_manager.dateOfCreation')</th>
                    @auth
                    <th>@lang('task_manager.actions')</th>
                    @endauth
                </tr>
            </thead>
            <tbody>
            @foreach($tasks as $task)
                <tr>
                    <td>{{ $task->id }}</td>
                    <td>{{ $task->taskStatus->name }}</td>
                    <td><a
                            class="text-blue-600 hover:text-blue-900"
                            href="{{ route('tasks.show', ['task' => $task->id]) }}"
                        >{{ $task->name }}</a>
                    </td>
                    <td>{{ $task->author->name ?? '' }}</td>
                    <td>{{ $task->executor->name ?? '' }}</td>
                    <td>{{ $task->created_at->format('d.m.Y') }}</td>
                    @auth
                    <td>
                        @if($task->created_by_id === $currentUser->id)
                        <a
                            data-confirm="@lang('task_manager.dataConfirm')"
                            data-method="delete"
                            href="{{ route('tasks.destroy', ['task' => $task->id]) }}"
                            class="text-red-600 hover:text-red-900"
                        >@lang('task_manager.remove')</a>
                        @endif
                        <a
                            href="{{ route('tasks.edit', ['task' => $task->id]) }}"
                            class="text-blue-600 hover:text-blue-900"
                        >@lang('task_manager.toChange')</a>
                    </td>
                    @endauth
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            {{ $tasks->links() }}
        </div>
    </div>
@endsection
