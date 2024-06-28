@extends('layouts.app')

@section('content')
    <div class="max-w-max mx-auto">

        <!-- Form for sorting and filtering -->
        <form method="GET" action="{{ route('schedule_items.index') }}">
            <div class="flex items-center p-4">
                <div>
                    <label for="filter">Filter by Schedule ID or Filename:</label>
                    <input type="text" id="filter" name="filter" value="{{ request('filter') }}">
                </div>

                <div>
                    <button type="submit" class="ml-5">Apply</button>
                </div>
                @if(request('filter'))
                    <div>
                        <a href="{{ route('schedule_items.index') }}" class="ml-5 text-red-600">Reset Filter</a>
                    </div>
                @endif
            </div>
        </form>

        @if(count($scheduleItems) > 0)

            <div class="container grid grid-cols-6 gap-4 bg-gray-500 p-4">
                <div class="text-white text-md col-span-1">
                    <a href="{{ route('schedule_items.index', ['sort_by' => 'schedule_id', 'direction' => request('sort_by') == 'schedule_id' && request('direction') == 'asc' ? 'desc' : 'asc', 'filter' => request('filter')]) }}" class="transititext-primary text-primary transition duration-150 ease-in-out hover:text-primary-600 focus:text-primary-600 active:text-primary-700 dark:text-primary-400 dark:hover:text-primary-500 dark:focus:text-primary-500 dark:active:text-primary-600"
                       data-te-toggle="tooltip"
                       title="Click to sort results by Schedule TD">Schedule ID</a>
                </div>
                <div class="text-white text-md col-span-2">
                    File
                </div>
                <div class="text-white text-md col-span-1">Start Date</div>
                <div class="text-white text-md col-span-1">End Date</div>
            </div>

            <div class="container grid grid-cols-6 gap-4 p-2">


                    @foreach($scheduleItems as $item)
                        <div class="col-span-1">
                            <a href="/schedules/{{$item->schedule_id}}" class="text-blue-500 hover:text-blue-700">{{$item->schedule_id}}</a>
                        </div>
                        <div class="col-span-2">
                            {{$item->file}}
                        </div>
                        <div class="col-span-1">
                            {{ Carbon\Carbon::parse($item->start_date)->format('d-m-Y') }}
                        </div>
                        <div class="col-span-1">
                            {{ Carbon\Carbon::parse($item->end_date)->format('d-m-Y') }}
                        </div>
                    <div class="col-span-1">
                        <a href="/items/{{$item->id}}"
                           class="text-blue-500 hover:text-blue-700">View</a>
                    </div>
                    @endforeach
                </div>
                    <div class="flex justify-center mt-4">
                        {{ $scheduleItems->links() }}
                </div>
        @else
            <p class="text-center text-xl text-gray-500">No sites found</p>
        @endif
    </div>
@endsection