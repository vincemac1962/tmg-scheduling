<!-- This is the create form for adding an advertiser -->
@extends('layouts.app')

@section('content')
<div class="container max-w-4xl mx-auto px-4">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form id="advertiserForm" method="POST" action="{{ route('advertisers.store') }}" enctype="multipart/form-data">
    @csrf
    <div id="new_advertiser_fields">
        <div class="flex justify-between items-center mt-3">
            <label for="contract" class="w-1/4 text-left mr-2">Contract:</label>
            <input type="text" id="contract" name="contract" class="form-control w-3/4" required>
        </div>
        <div class="flex justify-between items-center mt-3">
            <label for="business_name" class="w-1/4 text-left mr-2">Business:</label>
            <input type="text" id="business_name" name="business_name" class="form-control w-3/4" required>
        </div>
        <div class="flex justify-between items-center mt-3">
            <label for="address_1" class="w-1/4 text-left mr-2">Address (1):</label>
            <input type="text" id="address_1" name="address_1" class="form-control w-3/4">
        </div>
        <div class="flex justify-between items-center mt-3">
            <label for="address_2" class="w-1/4 text-left mr-2">Address (2):</label>
            <input type="text" id="address_2" name="address_2" class="form-control w-3/4">
        </div>
        <div class="flex justify-between items-center mt-3">
            <label for="street" class="w-1/4 text-left mr-2">Street:</label>
            <input type="text" id="street" name="street" class="form-control w-3/4">
        </div>
        <div class="flex justify-between items-center mt-3">
            <label for="city" class="w-1/4 text-left mr-2">City:</label>
            <input type="text" id="city" name="city" class="form-control w-3/4">
        </div>
        <div class="flex justify-between items-center mt-3">
            <label for="county" class="w-1/4 text-left mr-2">County:</label>
            <input type="text" id="county" name="county" class="form-control w-3/4">
        </div>
        <div class="flex justify-between items-center mt-3">
            <label for="postal_code" class="w-1/4 text-left mr-2">Postal Code:</label>
            <input type="text" id="postal_code" name="postal_code" class="form-control w-3/4">
        </div>
        <div class="flex justify-between items-center mt-3">
            <label for="country" class="w-1/4 text-left mr-2">Country:</label>
            <input type="text" id="country" name="country" class="form-control w-3/4">
        </div>
        <div class="flex justify-between items-center mt-3">
            <label for="phone" class="w-1/4 text-left mr-2">Phone:</label>
            <input type="text" id="phone" name="phone" class="form-control w-3/4">
        </div>
        <div class="flex justify-between items-center mt-3">
            <label for="mobile" class="w-1/4 text-left mr-2">Mobile:</label>
            <input type="text" id="mobile" name="mobile" class="form-control w-3/4">
        </div>
        <div class="flex justify-between items-center mt-3">
            <label for="email" class="w-1/4 text-left mr-2">Email:</label>
            <input type="email" id="email" name="email" class="form-control w-3/4">
        </div>
        <div class="flex justify-between items-center mt-3">
            <label for="url" class="w-1/4 text-left mr-2">URL:</label>
            <input type="url" id="url" name="url" class="form-control w-3/4">
        </div>
        <div class="flex justify-between items-center mt-3">
            <label for="social" class="w-1/4 text-left mr-2">Social:</label>
            <input type="url" id="social" name="social" class="form-control w-3/4">
        </div>
        <div class="flex justify-between items-center mt-3">
            <label for="sort_order" class="w-1/4 text-left mr-2">Sort Order:</label>
            <div class="form-control w-3/4">
                <input type="radio" id="sort_order_1" name="sort_order" value="1" checked>
                <label for="sort_order_1">1</label>
                <input type="radio" id="sort_order_0" name="sort_order" value="0">
                <label for="sort_order_0">0</label>
            </div>
        </div>
        <hr class="mt-5">
        <div class="flex justify-between items-center mt-3">
            <label for="banner" class="w-1/4 text-left mr-2">Banner:</label>
            <input type="file" id="banner" name="banner" class="form-control w-3/4">
            <button type="button" onclick="clearInput('banner')" class="ml-2 px-2 py-1 bg-red-500 text-white rounded">Clear</button>
            @isset($advertiser->banner)
                <span class="ml-2">{{ basename($advertiser->banner) }}</span>
            @endisset
        </div>
        <div class="flex justify-between items-center mt-3">
            <label for="button" class="w-1/4 text-left mr-2">Button:</label>
            <input type="file" id="button" name="button" class="form-control w-3/4">
            <button type="button" onclick="clearInput('button')" class="ml-2 px-2 py-1 bg-red-500 text-white rounded">Clear</button>
            @isset($advertiser->button)
                <span class="ml-2">{{ basename($advertiser->button) }}</span>
            @endisset
        </div>
        <div class="flex justify-between items-center mt-3">
            <label for="mp4" class="w-1/4 text-left mr-2">MP4:</label>
            <input type="file" id="mp4" name="mp4" class="form-control w-3/4">
            <button type="button" onclick="clearInput('mp4')" class="ml-2 px-2 py-1 bg-red-500 text-white rounded">Clear</button>
            @isset($advertiser->mp4)
                <span class="ml-2">{{ basename($advertiser->mp4) }}</span>
            @endisset
        </div>
        <input type="hidden" id="created_by" name="created_by" value="{{ auth()->user()->id ?? '' }}">
        @if(isset($schedule->id))
            <input type="hidden" id="schedule_id" name="schedule_id" value="{{ $schedule->id }}">
        @endif
    </div>
        <div class="flex justify-center mt-3 space-x-2">
            @if(isset($schedule->id))
                <a href="{{ route('schedules.show', $schedule->id) }}" class="px-4 py-2 rounded bg-gray-500 text-white w-40 text-center">Cancel</a>
            @else
                <a href="{{ route('advertisers.index') }}" class="px-4 py-2 rounded bg-gray-500 text-white w-40 text-center">Cancel</a>
            @endif
            <button type="submit" class="px-4 py-2 rounded bg-blue-500 text-white w-40">Add Advertiser</button>
        </div>

    </form>
</div>

<script>
    document.getElementById('advertiserForm').addEventListener('submit', function(event) {
        const maxFileSize = 10 * 1024 * 1024; // 10MB in bytes
        const banner = document.getElementById('banner').files[0];
        const button = document.getElementById('button').files[0];
        const mp4 = document.getElementById('mp4').files[0];

        if ((banner && banner.size > maxFileSize) ||
            (button && button.size > maxFileSize) ||
            (mp4 && mp4.size > maxFileSize)) {
            alert('One or more files exceed the 10MB size limit.');
            event.preventDefault();
        }
    });

    function clearInput(inputId) {
        document.getElementById(inputId).value = '';
    }
</script>

@endsection