<!-- This is the form for editing schedules. -->
@extends('layouts.app')

@section('content')
    <div class="container max-w-4xl mx-auto px-4">
            <form action="{{ route('schedules.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="flex justify-between items-center mt-3">
                    <label for="title" class="w-1/4 text-left mr-2">Title</label>
                    <input type="text" class="form-control w-3/4" id="title" name="title" value="{{ old('title') }}">
                    @error('title')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="flex justify-between items-center mt-3">
                    <label for ="description" class="w-1/4 text-left mr-2">Description</label>
                    <textarea class="form-control w-3/4" id="description" name="description">{{ old('description') }}</textarea>
                    @error('description')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="flex justify-center mt-3 space-x-2">
                    <input type="hidden" name="created_by" value="{{ $user_id }}">
                    <button type="submit" class="px-4 py-2 rounded bg-blue-500 text-white">Submit</button>
                    <a href="{{ route('schedules.index') }}" class="px-4 py-2 rounded bg-gray-500 text-white">Cancel</a>
                </div>
            </form>
    </div>
@endsection
