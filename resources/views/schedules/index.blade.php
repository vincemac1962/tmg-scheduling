<!-- resources/views/schedules/index.blade.php -->

@php use Illuminate\Support\Str; @endphp
@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-4">{{ $header }}</h1>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('schedules.index') }}" class="mb-4">
            <div class="flex items-center">
                <input type="text" name="filter" placeholder="Filter schedules..." class="border p-2 mr-2">
                <label class="mr-2">
                    <input type="checkbox" name="view_all" value="1" {{ request('view_all') ? 'checked' : '' }}>
                    View All
                </label>
                <a href="#" onclick="this.closest('form').submit()">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">
                        Apply
                    <button>
                </a>
            </div>
        </form>

        @if(count($schedules) > 0)
            <table class="min-w-full bg-white">
                <thead>
                <tr>
                    <th class="py-2 px-4 border-b text-center">ID</th>
                    <th class="py-2 px-4 border-b">Title</th>
                    <th class="py-2 px-4 border-b text-center">Updated At</th>
                    <th class="py-2 px-4 border-b text-center">Created At</th>
                    <th class="py-2 px-4 border-b text-center">Created By</th>
                    <th class="py-2 px-4 border-b text-center">Sites (Downloaded/Total)</th>
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
            {{ $schedules->links() }}
        @else
            <p class="text-center text-xl text-gray-500">No schedules found</p>
        @endif
    </div>
@endsection