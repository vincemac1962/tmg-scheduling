@extends('layouts.app')

@section('content')
    <div class="container max-w-4xl mx-auto px-4">
        <form action="{{ route('schedule_items.store') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
            @csrf
            <!-- Upload fields -->
            <div class="flex justify-between items-center mt-3">
                <label for="resource_filename" class="w-1/4 text-left mr-2">Resource Filename</label>
                <input type="file" class="form-control w-3/4" id="resource_filename" name="resource_filename">
                @error('resource_filename')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <!-- Schedule fields -->
            <div class="flex justify-between items-center mt-3">
                <label for="title" class="w-1/4 text-left mr-2">Title</label>
                <input type="text" class="form-control w-3/4" id="title" name="title" value="{{ old('title') }}">
                @error('title')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="flex justify-between items-center mt-3">
                <label for="start_date" class="w-1/4 text-left mr-2">Start Date</label>
                <input type="date" class="form-control w-3/4" id="start_date" name="start_date" value="{{ old('start_date') }}">
                @error('start_date')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="flex justify-between items-center mt-3">
                <label for="end_date" class="w-1/4 text-left mr-2">End Date</label>
                <input type="date" class="form-control w-3/4" id="end_date" name="end_date" value="{{ old('end_date') }}">
                @error('start_date')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="flex justify-center mt-3 space-x-2">
                <input type="hidden" name="uploaded_by" value="{{ $user_id }}">
                <input type="hidden" name="resource_type" value="mp4">
                <input type="hidden" name="schedule_id" value="{{ $schedule_id }}">
                <button type="submit" class="px-4 py-2 rounded bg-blue-500 text-white">Submit</button>
                <a href="/schedules/{{ $schedule_id }}" class="px-4 py-2 rounded bg-gray-500 text-white">Cancel</a>
            </div>
        </form>
    </div>
@endsection
