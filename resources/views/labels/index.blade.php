@extends('layouts.tasks')

@section('content')
    @if (session('messageLabel'))
        <div class="alert alert-success">
            {{ session('messageLabel') }}
        </div>
    @endif
    <div class="grid col-span-full">
        <h1 class="mb-5">Метки</h1>
        <div>
            @auth
                <a
                    href="{{ route('labels.create') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                >@lang('task_manager.createLabel')</a>
            @endauth
        </div>
        <table class="mt-4">
            <thead class="border-b-2 border-solid border-black text-left">
            <tr>
                <th>ID</th>
                <th>@lang('task_manager.name')</th>
                <th>@lang('task_manager.description')</th>
                <th>@lang('task_manager.dateOfCreation')</th>
                @auth
                    <th>@lang('task_manager.actions')</th>
                @endauth
            </tr>
            </thead>
            <tbody>
            @foreach($labels as $label)
                <tr class="border-b border-dashed text-left">
                    <td>{{ $label->id }}</td>
                    <td>{{ $label->name }}</td>
                    <td>{{ $label->description }}</td>
                    <td>{{ $label->created_at->format('d.m.Y') }}</td>
                    @auth
                    <td>
                        <a
                            data-confirm="@lang('task_manager.dataConfirm')"
                            data-method="delete"
                            class="text-red-600 hover:text-red-900"
                            href="{{ route('labels.destroy', ['label' => $label->id]) }}"
                        >@lang('task_manager.remove')</a>
                        <a
                            class="text-blue-600 hover:text-blue-900"
                            href="{{ route('labels.edit', ['label' => $label->id]) }}"
                        >@lang('task_manager.toChange')</a>
                    </td>
                    @endauth
                </tr>
            @endforeach
            </tbody></table>
    </div>
@endsection
