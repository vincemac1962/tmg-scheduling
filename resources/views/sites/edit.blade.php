<!-- This is the form for editing sites. -->
@extends('layouts.app')

@section('content')
    <div class="container max-w-4xl mx-auto px-4">
            <form action="{{ route('sites.update', $site->id) }}" method="POST" class="space-y-4">
                @csrf
                <div class="flex justify-between items-center mt-3">
                    <label for="site_ref" class="w-1/4 text-left mr-2">Site Reference</label>
                    <input type="text" class="form-control w-3/4" id="site_ref" name="site_ref" value="{{ $site->site_ref }}" readonly>
                    @error('site_ref')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="flex justify-between items-center mt-3">
                    <label for="site_name" class="w-1/4 text-left mr-2">Site Name</label>
                    <input type="text" class="form-control w-3/4" id="site_name" name="site_name" value="{{ $site->site_name }}">
                    @error('site_name')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="flex justify-between items-center mt-3">
                    <label for="site_address" class="w-1/4 text-left mr-2">Site Address</label>
                    <input type="text" class="form-control w-3/4" id="site_address" name="site_address" value="{{ $site->site_address }}">
                    @error('site_address')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="flex justify-between items-center mt-3">
                    <label for="site_postcode" class="w-1/4 text-left mr-2">Site Postcode</label>
                    <input type="text" class="form-control w-3/4" id="site_postcode" name="site_postcode" value="{{ $site->site_postcode }}">
                    @error('site_postcode')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="flex justify-between items-center mt-3">
                    <label for="site_country" class="w-1/4 text-left mr-2">Site Country</label>
                    <input type="text" class="form-control w-3/4" id="site_postcode" name="site_country" value="{{ $site->site_country }}">
                    @error('site_country')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="flex justify-between items-center mt-3">
                    <label for="site_contact" class="w-1/4 text-left mr-2">Site Contact</label>
                    <input type="text" class="form-control w-3/4" id="site_contact" name="site_contact" value="{{ $site->site_contact }}">
                    @error('site_tel')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="flex justify-between items-center mt-3">
                    <label for="site_email" class="w-1/4 text-left mr-2">Site Email</label>
                    <input type="text" class="form-control w-3/4" id="site_email" name="site_email" value="{{ $site->site_email }}">
                    @error('site_email')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="flex justify-between items-center mt-3">
                    <label for ="site_active" class="w-1/4 text-left mr-2">Site Active</label>
                    <input type="hidden" name="site_active" value="0"> <!-- Hidden field to ensure a value is always passed -->
                    <input type="checkbox" id="site_active" name="site_active" value="1" {{ $site->site_active ? 'checked' : '' }}>
                    @error('site_active')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="flex justify-between items-center mt-3">
                    <label for ="site_notes" class="w-1/4 text-left mr-2">Site Notes</label>
                    <textarea class="form-control w-3/4" id="site_notes" name="site_notes">{{ $site->site_notes }}</textarea>
                    @error('site_notes')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="flex justify-center mt-3 space-x-2">
                    <input type="hidden" name="_method" value="PUT">
                    <button type="submit" class="px-4 py-2 rounded bg-blue-500 text-white">Submit</button>
                    <a href="{{ route('sites.index') }}" class="px-4 py-2 rounded bg-gray-500 text-white">Cancel</a>
                </div>
            </form>
    </div>
@endsection
