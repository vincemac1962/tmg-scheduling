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
    <form method="POST" action="{{ route('advertisers.update', $advertiser->id) }}" enctype="multipart/form-data">
    @method('PUT')
    @csrf
    <div id="new_advertiser_fields">
        <div class="flex justify-between items-center mt-3">
            <label for="contract" class="w-1/4 text-left mr-2">Contract:</label>
            <input type="text" id="contract" name="contract" class="form-control w-3/4" value="{{ $advertiser->contract }}" required>
        </div>
        <div class="flex justify-between items-center mt-3">
            <label for="business_name" class="w-1/4 text-left mr-2">Business:</label>
            <input type="text" id="business_name" name="business_name" class="form-control w-3/4" value="{{ $advertiser->business_name }}"required>
        </div>
        <div class="flex justify-between items-center mt-3">
            <label for="address_1" class="w-1/4 text-left mr-2">Address (1):</label>
            <input type="text" id="address_1" name="address_1" class="form-control w-3/4" value="{{ $advertiser->address_1 }}">
        </div>
        <div class="flex justify-between items-center mt-3">
            <label for="address_2" class="w-1/4 text-left mr-2">Address (2):</label>
            <input type="text" id="address_2" name="address_2" class="form-control w-3/4" value="{{ $advertiser->address_2t }}">
        </div>
        <div class="flex justify-between items-center mt-3">
            <label for="street" class="w-1/4 text-left mr-2">Street:</label>
            <input type="text" id="street" name="street" class="form-control w-3/4" value="{{ $advertiser->street }}">
        </div>
        <div class="flex justify-between items-center mt-3">
            <label for="city" class="w-1/4 text-left mr-2">City:</label>
            <input type="text" id="city" name="city" class="form-control w-3/4" value="{{ $advertiser->city }}">
        </div>
        <div class="flex justify-between items-center mt-3">
            <label for="county" class="w-1/4 text-left mr-2">County:</label>
            <input type="text" id="county" name="county" class="form-control w-3/4" value="{{ $advertiser->county }}">
        </div>
        <div class="flex justify-between items-center mt-3">
            <label for="postal_code" class="w-1/4 text-left mr-2">Postal Code:</label>
            <input type="text" id="postal_code" name="postal_code" class="form-control w-3/4" value="{{ $advertiser->postal_code }}">
        </div>
        <div class="flex justify-between items-center mt-3">
            <label for="country" class="w-1/4 text-left mr-2">Country:</label>
            <input type="text" id="country" name="country" class="form-control w-3/4" value="{{ $advertiser->country }}">
        </div>
        <div class="flex justify-between items-center mt-3">
            <label for="phone" class="w-1/4 text-left mr-2">Phone:</label>
            <input type="text" id="phone" name="phone" class="form-control w-3/4" value="{{ $advertiser->phone }}">
        </div>
        <div class="flex justify-between items-center mt-3">
            <label for="mobile" class="w-1/4 text-left mr-2">Mobile:</label>
            <input type="text" id="mobile" name="mobile" class="form-control w-3/4" value="{{ $advertiser->mobile }}">
        </div>
        <div class="flex justify-between items-center mt-3">
            <label for="email" class="w-1/4 text-left mr-2">Email:</label>
            <input type="email" id="email" name="email" class="form-control w-3/4" value="{{ $advertiser->email }}">
        </div>
        <div class="flex justify-between items-center mt-3">
            <label for="url" class="w-1/4 text-left mr-2">URL:</label>
            <input type="url" id="url" name="url" class="form-control w-3/4" value="{{ $advertiser->url }}">
        </div>
        <div class="flex justify-between items-center mt-3">
            <label for="social" class="w-1/4 text-left mr-2">Social:</label>
            <input type="url" id="social" name="social" class="form-control w-3/4" value="{{ $advertiser->social }}">
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
        <hr>
        <div class="flex justify-between items-center mt-3">
            <label for="banner" class="w-1/4 text-left mr-2">Banner:</label>
            <input type="file" id="banner" name="banner" class="form-control w-3/4" value="{{ $advertiser->banner }}">
        </div>
        <div class="flex justify-between items-center mt-3">
            <label for="button" class="w-1/4 text-left mr-2">Button:</label>
            <input type="file" id="button" name="button" class="form-control w-3/4" value="{{ $advertiser->button }}">
        </div>
        <div class="flex justify-between items-center mt-3">
            <label for="mp4" class="w-1/4 text-left mr-2">MP4:</label>
            <input type="file" id="mp4" name="mp4" class="form-control w-3/4" value="{{ $advertiser->mp4}}">
        </div>
        <input type="hidden" id="created_by" name="created_by" value="{{ auth()->check() ? auth()->user()->id : '' }}">
        <input type="hidden" id="schedule_id" name="schedule_id" value="{{ session('schedule_id') }}">
    </div>
        <div class="flex justify-center mt-3 space-x-2">
            <button type="submit" class="px-4 py-2 rounded bg-blue-500 text-white">Save Advertiser</button>
            <a href="{{ route('advertisers.index', $scheduleId) }}" class="px-4 py-2 rounded bg-gray-500 text-white">Cancel</a>
        </div>

    </form>
</div>

@endsection