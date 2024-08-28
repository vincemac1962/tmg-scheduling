@php use Carbon\Carbon; @endphp
@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-4">{{ $header }}</h1>
        <table class="min-w-full bg-white">
            <thead>
            <tr>
                <th class="py-2 px-4 border-b">Site Ref</th>
                <th class="py-2 px-4 border-b">Site Name</th>
                <th class="py-2 px-4 border-b">Site Address</th>
                <th class="py-2 px-4 border-b">Downloaded</th>
                <th class="py-2 px-4 border-b">Downloaded At</th>
            </tr>
            </thead>
            <tbody>
            @foreach($sites as $site)
                <tr>
                    <td class="py-2 px-4 border-b">{{ $site->site_ref }}</td>
                    <td class="py-2 px-4 border-b">{{ $site->site_name }}</td>
                    <td class="py-2 px-4 border-b">{{ $site->site_address }}</td>
                    <td class="py-2 px-4 border-b {{ $site->pivot->downloaded ? 'text-green-500' : 'text-red-500' }}">
                        {{ $site->pivot->downloaded ? 'True' : 'False' }}
                    </td>
                    <td class="py-2 px-4 border-b">
                        {{ $site->pivot->downloaded_at ? Carbon::parse($site->pivot->downloaded_at)->format('d/m/Y H:i') : 'N/A' }}
                    </td>
                    <!-- Add a button to remove the site from the schedule -->
                    <td class="py-2 px-4 border-b">
                        <form action="{{ route('schedules.removeSite', ['schedule' => session('schedule_id'), 'site' => $site->id]) }}"
                              method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Remove
                            </button>
                        </form>
                </tr>
            @endforeach
            </tbody>
        </table>
        <!-- Add a button to return to the schedule index -->
        <div class="flex justify-center items-center">
            <a href="{{ route('schedules.show', ['schedule' => session('schedule_id')]) }}"
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">Back</a>
        </div>
    </div>
@endsection
