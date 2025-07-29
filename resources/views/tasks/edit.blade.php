@extends('layouts.tasks')

@section('content')
    <div class="grid col-span-full">
        <h1 class="mb-5">@lang('task_manager.editedTask')</h1>
        <form class="w-50" method="POST" action="{{ route('tasks.update', ['task' => $task->id]) }}">
            @csrf
            @method('PATCH')
            <div class="flex flex-col">
                <div>
                    <label for="name">@lang('task_manager.name')</label>
                </div>
                <div class="mt-2">
                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="rounded border-gray-300 w-1/3"
                        value="{{ old('name') ?? $task->name }}"
                    >
                </div>
                @error('name')
                <div class="text-rose-600">{{ $message }}</div>
                @enderror

                <div class="mt-2">
                    <label for="description">@lang('task_manager.description')</label>
                </div>
                <div>
                    <textarea
                        class="rounded border-gray-300 w-1/3 h-32"
                        name="description"
                        id="description"
                    >{{ old('description') ?? $task->description }}</textarea>
                </div>
                @error('description')
                <div class="text-rose-600">{{ $message }}</div>
                @enderror

                <div class="mt-2">
                    <label for="status_id">@lang('task_manager.status')</label>
                </div>
                <div>
                    <select class="rounded border-gray-300 w-1/3" name="status_id" id="status_id">
                        @foreach($taskStatuses as $taskStatus)
                            <option
                                value="{{ $taskStatus->id }}"
                                {{
                                    (
                                        ($taskStatus->id == old('status_id')) ||
                                        ($taskStatus->id == $task->status_id)
                                    ) ? 'selected' : ''
                                }}

                                @selected(($taskStatus->id == old('status_id')) || ($taskStatus->id == $task->status_id))

                            >{{ $taskStatus->name }}</option>
                        @endforeach
                    </select>
                </div>
                @error('status_id')
                <div class="text-rose-600">{{ $message }}</div>
                @enderror

                <div class="mt-2">
                    <label for="status_id">@lang('task_manager.executor')</label>
                </div>
                <div>
                    <select class="rounded border-gray-300 w-1/3" name="assigned_to_id" id="assigned_to_id" autocomplete="off">
                        <option value="">Не выбран</option>
                        @foreach($users as $user)
                            <option
                                value="{{ $user->id }}"
                                {{
                                    (
                                        ($user->id == old('assigned_to_id')) ||
                                        ($user->id == $task->assigned_to_id )
                                    ) ? 'selected' : ''
                                }}
                            >{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                @error('assigned_to_id')
                <div class="text-rose-600">{{ $message }}</div>
                @enderror

                <div class="mt-2">
                    <label for="labels[]">@lang('task_manager.labels')</label>
                </div>
                <div>
                    <select
                        class="rounded border-gray-300 w-1/3 h-32"
                        name="labels[]"
                        id="labels[]"
                        multiple
                        autocomplete="off"
                    >
                        @foreach($labels as $label)
                            <option
                                value="{{ $label->id }}"
                                {{ \in_array($label->id, $taskLabels) ? 'selected' : '' }}
                            >{{ $label->name }}</option>
                        @endforeach
                    </select>
                </div>
                @error('labels[]')
                <div class="text-rose-600">{{ $message }}</div>
                @enderror

                <div class="mt-2">
                    <button
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                        type="submit"
                    >@lang('task_manager.update')</button>
                </div>

            </div>
        </form>
    </div>
@endsection
