@extends('layouts.tasks')

@section('content')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <div class="grid col-span-full">
        <h1 class="mb-5">@lang('task_manager.statuses')</h1>
        <div>
            @auth
            <a
                href="{{ route('task_statuses.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
            >@lang('task_manager.createStatus')</a>
            @endauth
        </div>
        <table class="mt-4">
            <thead class="border-b-2 border-solid border-black text-left">
                <tr>
                    <th>ID</th>
                    <th>@lang('task_manager.name')</th>
                    <th>@lang('task_manager.dateOfCreation')</th>
                    @auth
                    <th>@lang('task_manager.actions')</th>
                    @endauth
                </tr>
            </thead>
            <tbody>
            @foreach($statuses as $status)
                <tr class="border-b border-dashed text-left">
                    <td>{{ $status->id }}</td>
                    <td>{{ $status->name }}</td>
                    <td>{{ $status->created_at->format('d.m.Y') }}</td>
                    @auth
                    <td>
                        <a
                            data-confirm="Вы уверены?"
                            data-method="delete"
                            class="text-red-600 hover:text-red-900"
                            href="{{ route('task_statuses.destroy', ['task_status' => $status->id]) }}"
                            rel="nofollow"
                        >@lang('task_manager.remove')</a>
                        <a
                            class="text-blue-600 hover:text-blue-900"
                            href="{{ route('task_statuses.edit', ['task_status' => $status->id]) }}"
                        >@lang('task_manager.toChange')</a>
                    </td>
                    @endauth
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
