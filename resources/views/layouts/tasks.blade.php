<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        {{-- для ujs --}}
        <meta name="csrf-param" content="_token">

        <title>@lang('task_manager.taskManager')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=Nunito:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased">
        <div id="app">
            <!-- Page Heading -->
            <header class="fixed w-full">
                <nav class="bg-white border-gray-200 py-2.5 dark:bg-gray-900 shadow-md">
                    <div class="flex flex-wrap items-center justify-between max-w-screen-xl px-4 mx-auto">
                        <a href="{{ route('home') }}" class="flex items-center">
                            <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">@lang('task_manager.taskManager')</span>
                        </a>

                        <div class="flex items-center lg:order-2">
                            @if(Route::has('login'))
                                @auth
                                    <a href="{{ url('/logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2"
                                    >@lang('task_manager.logout')</a>
                                    <form
                                        id="logout-form"
                                        action="{{ url('/logout') }}"
                                        method="POST"
                                        style="display: none;"
                                    >
                                        @csrf
                                    </form>
                                @else
                                    <a href="{{ route('login') }}"
                                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                    >@lang('task_manager.logIn')</a>
                                    @if(Route::has('register'))
                                        <a href="{{ route('register') }}"
                                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2"
                                        >@lang('task_manager.registration')</a>
                                    @endif
                                @endauth
                            @endif
                        </div>

                        <div class="items-center justify-between hidden w-full lg:flex lg:w-auto lg:order-1">
                            <ul class="flex flex-col mt-4 font-medium lg:flex-row lg:space-x-8 lg:mt-0">
                                <li>
                                    <a href="#tasks"
                                       class="block py-2 pl-3 pr-4 text-gray-700 hover:text-blue-700 lg:p-0"
                                    >@lang('task_manager.tasks')</a>
                                </li>
                                <li>
                                    <a href="{{ route('task_statuses.index') }}"
                                       class="block py-2 pl-3 pr-4 text-gray-700 hover:text-blue-700 lg:p-0"
                                    >@lang('task_manager.statuses')</a>
                                </li>
                                <li>
                                    <a href="#labels"
                                       class="block py-2 pl-3 pr-4 text-gray-700 hover:text-blue-700 lg:p-0"
                                    >@lang('task_manager.tags')</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </header>

            <!-- Page Content -->
            <section class="bg-white dark:bg-gray-900">
                <div class="grid max-w-screen-xl px-4 pt-20 pb-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12 lg:pt-28">
                    @yield('content')

                    @if (isset($slot))
                        {{ $slot }}
                    @endif
                </div>
            </section>
        </div>
    </body>
</html>
