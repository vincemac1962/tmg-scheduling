@php use Illuminate\Support\Str; @endphp
        <!-- This is the view for the show schedule. It will display a schedule chosen from the schedule index -->
@extends('layouts.app')

@section('content')
    <div class="container max-w-max mx-auto px-4">
        <h1 class="mt-5 text-2xl">Schedule</h1>
        <p><strong>ID: </strong>{{$schedule->id}}</p>
        <p><strong>Title: </strong>{{$schedule->title}}</p>
        <p style="word-wrap: break-word"><strong>Description: </strong>{{ $schedule->description }}</p>
        <p><strong>Created By: </strong>{{$schedule->site_id}}</p>
        <p><strong>Last Modified: </strong>{{$schedule->updated_at->format('d/m/Y H:i') }}</p>
        <p><strong>Created: </strong>{{$schedule->created_at->format('d/m/Y H:i') }}</p>
        @if(count($schedule->scheduleItems) > 0)
            <div class="container grid grid-cols-12 gap-4 bg-gray-500 p-2 mt-5">
                <div class="text-white text-md col-span-2">Type</div>
                <div class="text-white text-md col-span-5">
                    File
                </div>
                <div class="text-white text-md col-span-2">Last Modified</div>
                <div class="text-white text-md col-span-2">Created On</div>
                <div class="text-white text-md col-span-1"></div>
            </div>
            <div class="container grid grid-cols-12 gap-4 p-4">
            @foreach($schedule->scheduleItems as $item)
                <div class="col-span-2">
                    {{  $item->upload->resource_type  }}
                </div>
                <div class="col-span-5">
                    {{ Str::limit($item->file, 50) }}
                </div>
                <div class="col-span-2">
                    {{ Carbon\Carbon::parse($item->start_date)->format('d-m-Y') }}
                </div>
                <div class="col-span-2">
                    {{ Carbon\Carbon::parse($item->end_date)->format('d-m-Y') }}
                </div>
                <div class="col-span-1">
                    <a href="/schedule_items/{{$item->id}}"
                       class="text-blue-500 hover:text-blue-700">View</a>
                </div>
            @endforeach
        @else
                <p class="text-center text-xl text-gray-500">No schedule items found</p>
        @endif
        <hr>
        </div>
            <div class="flex justify-center mt-4">
                <a href="/schedules" class="w-40 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded mr-2 text-center">Back</a>
                <a href="/schedules/{{$schedule->id}}/edit" class="w-40 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded mr-2 text-center">Edit Schedule</a>
                <a href="#" class="w-40 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded mr-2 text-center">Add Item</a>
                <a href="#" class="w-40 bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded mr-2 text-center">Add Advertiser</a>
                <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST" class="mr-2">
                    @csrf
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="submit" value="Delete" class="w-40 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded cursor-pointer text-center">
                </form>
            </div>

@endsection
