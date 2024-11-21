@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-4">{{ $header }}</h1>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('schedules.index') }}" class="mb-4">
            <div class="flex items-center">
                <label for="filter" class="mr-2"></label>
                <input type="text" name="filter" placeholder="Filter schedules..." class="border p-2 mr-2">
                <label class="mr-2">
                    <input type="checkbox" name="view_all" value="1" {{ request('view_all') ? 'checked' : '' }}>
                    View All
                </label>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">
                    Apply
                </button>
            </div>
        </form>



        @if(count($schedules) > 0)
            <table class="min-w-full bg-white">
                <thead class="bg-gray-500">
                <tr>
                    <th class="py-2 px-4 border-b text-center text-white text-md">
                        <a href="{{ route('schedules.index', ['sort_by' => 'id', 'direction' => request('sort_by') == 'id' && request('direction') == 'asc' ? 'desc' : 'asc', 'filter' => request('filter')]) }}"
                           data-te-toggle="tooltip"
                           title="Click to sort results by ID">ID &nbsp&#9432;
                        </a>
                    </th>
                    <th class="py-2 px-4 border-b text-white text-md">
                        <a href="{{ route('schedules.index', ['sort_by' => 'title', 'direction' => request('sort_by') == 'title' && request('direction') == 'asc' ? 'desc' : 'asc', 'filter' => request('filter')]) }}"
                           data-te-toggle="tooltip"
                           title="Click to sort results by Title">Title &nbsp&#9432;
                        </a>
                    </th>
                    <th class="py-2 px-4 border-b text-center text-white text-md">
                        <a href="{{ route('schedules.index', ['sort_by' => 'updated_at', 'direction' => request('sort_by') == 'updated_at' && request('direction') == 'asc' ? 'desc' : 'asc', 'filter' => request('filter')]) }}" data-te-toggle="tooltip"
                           title="Click to sort results by Updated At">Updated At &nbsp&#9432;
                        </a>
                    </th>
                    <th class="py-2 px-4 border-b text-center text-white text-md">Created At</th>
                    <th class="py-2 px-4 border-b text-center text-white text-md">Created By</th>
                    <th class="py-2 px-4 border-b text-center text-white text-md">Sites (Downloaded/Total)</th>
                </tr>
                </thead>
                <tbody>
                @foreach($schedules as $schedule)
                    <tr>
                        <td class="py-2 px-4 border-b text-center">
                            <a href="{{ route('schedules.show', $schedule->id) }}" class="text-blue-500">{{ $schedule->id }}</a>
                        </td>
                        <td class="py-2 px-4 border-b">{{ $schedule->title }}</td>
                        <td class="py-2 px-4 border-b text-center">{{ $schedule->updated_at->format('d/m/Y H:i') }}</td>
                        <td class="py-2 px-4 border-b text-center">{{ $schedule->created_at->format('d/m/Y H:i') }}</td>
                        <td class="py-2 px-4 border-b text-center">{{ $schedule->creator->name }}</td>
                        <td class="py-2 px-4 border-b text-center {{ ($schedule->downloaded_sites_count < $schedule->sites_count || $schedule->sites_count == 0) ? 'text-red-500' : 'text-green-500' }}">
                            {{ $schedule->downloaded_sites_count ?? 0 }}/{{ $schedule->sites_count ?? 0 }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <!-- Add New Schedule Button -->
            <div class="flex justify-center mb-4 mt-10">
                <a href="{{ route('schedules.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-full">
                    Add New Schedule
                </a>
            </div>

            {{ $schedules->links() }}
        @else
            <p class="text-center text-xl text-gray-500">No schedules found</p>
        @endif
    </div>
@endsection