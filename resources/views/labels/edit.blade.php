@extends('layouts.tasks')

@section('content')
    <div class="grid col-span-full">
        <h1 class="mb-5">@lang('task_manager.editedLabel')</h1>
        <form
            class="w-50"
            method="POST"
            action="{{ route('labels.update', ['label' => $label->id]) }}"
        >
            @method('PATCH')
            @csrf
            <div class="flex flex-col">
                <div>
                    <label for="name">@lang('task_manager.name')</label>
                </div>
                <div class="mt-2">
                    <input class="rounded border-gray-300 w-1/3" type="text" name="name" id="name" value="{{ $label->name }}">
                </div>
                @error('name')
                <div class="text-rose-600">{{ $message }}</div>
                @enderror
                <div class="mt-2">
                    <label for="description">@lang('task_manager.description')</label>
                </div>
                <div class="mt-2">
                    <textarea
                        class="rounded border-gray-300 w-1/3 h-32"
                        name="description"
                        id="description"
                    >{{ $label->description }}</textarea>
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
