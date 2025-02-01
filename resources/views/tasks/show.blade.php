@extends('layouts.tasks')

@section('content')
    <div class="grid col-span-full">
        <h2 class="mb-5">
            @lang('task_manager.viewTask'): {{ $task->name }}
            <a href="{{ route('tasks.edit', ['task' => $task->id]) }}">⚙</a>
        </h2>
        <p><span class="font-black">@lang('task_manager.name'):</span> {{ $task->name }}</p>
        <p><span class="font-black">@lang('task_manager.status'):</span> {{ $task->taskStatus->name }}</p>
        <p><span class="font-black">@lang('task_manager.description'):</span> {{ $task->description }}</p>
        <p><span class="font-black">@lang('task_manager.labels'):</span></p>
        <div>
        @foreach($taskLabels as $label)
            <div class="text-xs inline-flex items-center font-bold leading-sm uppercase px-3 py-1 bg-blue-200 text-blue-700 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                {{ $label['name'] }}
            </div>
        @endforeach
        </div>
    </div>
@endsection
