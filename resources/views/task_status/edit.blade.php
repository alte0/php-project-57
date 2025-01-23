@extends('layouts.tasks')

@section('content')
    <div class="grid col-span-full">
        <h1 class="mb-5">@lang('task_manager.editedStatus')</h1>
        <form class="w-50" method="POST" action="{{ route('task_statuses.update', ['task_status' => $taskStatus->id]) }}">
            @csrf
            @method('PATCH')
            <div class="flex flex-col">
                <div>
                    <label for="name">@lang('task_manager.name')</label>
                </div>
                <div class="mt-2">
                    <input class="rounded border-gray-300 w-1/3" type="text" name="name" id="name" value="{{ $taskStatus->name }}">
                </div>
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
