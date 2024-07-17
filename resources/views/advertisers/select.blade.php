@extends('layouts.app')

@section('content')
<div class="max-w-max mx-auto">

       <!-- Form for sorting and filtering -->
        <form method="GET" action="{{ route('advertisers.index') }}">
            <div class="flex items-center p-4">
                <div>
                    <label for="filter">Filter by Business Name:</label>
                    <input type="text" id="filter" name="filter" value="{{ request('filter') }}">
                </div>

                <div>
                    <button type="submit" class="ml-5">Apply</button>
                </div>
                @if(request('filter'))
                    <div>
                        <a href="{{ route('advertisers.index') }}" class="ml-5 text-red-600">Reset Filter</a>
                    </div>
                @endif
            </div>
        </form>

    @if(count($advertisers) > 0)

        <div class="container grid grid-cols-10 gap-4 bg-gray-500 p-4">
            <div class="text-white text-md col-span-1">
                Contract
            </div>
            <div class="text-white text-md col-span-4">
                Business Name
            </div>
            <div class="text-white text-md col-span-2">City</div>
            <div class="text-white text-md col-span-2">Country</div>
            <div class="text-white text-md col-span-2">Select</div>
        </div>

        <div class="container grid grid-cols-10 gap-4 p-2">


            @foreach($advertisers as $advertiser)
                <div class="col-span-1">
                    <a href="/advertisers/{{$advertiser->id}}" class="text-blue-500 hover:text-blue-700">{{$advertiser->contract}}</a>
                </div>
                <div class="col-span-4">
                    {{$advertiser->business_name}}
                </div>
                <div class="col-span-2">
                    {{$advertiser->city}}
                </div>
                <div class="col-span-2">
                    {{$advertiser->country}}
                </div>
                <div class="col-span-1">
                    <input type="checkbox" name="advertiser_id" value="{{$advertiser->id}}">
                </div>
            @endforeach
        </div>
        <div class="flex justify-center mt-4">
            {{ $advertisers->links() }}
        </div>
    @else
        <p class="text-center text-xl text-gray-500">No advertisers found</p>
        @endif
        </div>

@endsection