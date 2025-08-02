@extends('layouts.tasks')

@section('content')
    @if ($isShowInfo)
        <div class="grid col-span-full">
            <div class="flex items-center bg-blue-500 text-white text-sm font-bold px-4 py-3" role="alert">
                <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z"/></svg>
                <p>Выберете не одинаковые метки!</p>
            </div>
        </div>
    @endif
    <div class="grid col-span-full">
        <h1 class="mb-5">@lang('task_manager.reports')</h1>
        <form class="w-50" method="GET" action="">
            <div class="flex">
                <select
                    class="rounded border-gray-300"
                    name="label[first]"
                    id="label[first]"
                >
                    @foreach($labels as $label)
                        <option
                            value="{{ $label->id }}"
                            @selected($label1 == $label->id)
                        >{{ $label->name }}</option>
                    @endforeach
                </select>
                <select
                    class="rounded border-gray-300"
                    name="label[second]"
                    id="label[second]"
                >
                    @foreach($labels as $label)
                        <option
                            value="{{ $label->id }}"
                            @selected($label2 == $label->id)
                        >{{ $label->name }}</option>
                    @endforeach
                </select>
                <button
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2"
                    type="submit"
                >Сравнить
                </button>
            </div>
        </form>
        @if(!$isShowInfo && $label1 > 0 && $label2 > 0)
        <div>
            <canvas class="size-full" id="myChart"></canvas>
            <script type="module">
                (async function() {
                    const DATA_COUNT = {{ $valuesTaskIdByLabelIdCount }};
                    const NUMBER_CFG = {count: DATA_COUNT, min: 0, max: 100};

                    const data = {
                        labels: ['{{ $labels[$label1]->name }}', '{{ $labels[$label2]->name }}',],
                        datasets: [
                            {
                                label: '{{ __('task_manager.tasks') }}',
                                data: [{{ $label1Count }}, {{ $label2Count }}],
                                backgroundColor: Object.values(Utils.CHART_COLORS),
                            }
                        ]
                    };

                    const config = {
                        type: 'pie',
                        data: data,
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                title: {
                                    display: true,
                                    text: '{{ __('task_manager.reports') }}'
                                }
                            }
                        },
                    };

                    new Chart(document.getElementById('myChart'), config);
                })();
            </script>
        </div>
        @endif
    </div>
@endsection
