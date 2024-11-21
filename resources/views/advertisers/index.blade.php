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

            <div class="container grid grid-cols-12 gap-4 bg-gray-500 p-4">
                <div class="text-white text-md col-span-1">
                    <a href="{{ route('advertisers.index', ['sort_by' => 'contract', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}"
                       data-te-toggle="tooltip"
                       title="Click to sort results by Contract Reference">Contract &nbsp&#9432;
                    </a>
                </div>
                <div class="text-white text-md col-span-4">
                    <a href="{{ route('advertisers.index', ['sort_by' => 'business_name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}"
                    data-te-toggle="tooltip"
                    title="Click to sort results by Business Name">Business Name&nbsp&#9432;</a>
                </div>
                <div class="text-white text-md col-span-2">City</div>
                <div class="text-white text-md col-span-2">Country</div>
                <div class="text-white text-md col-span-3">
                    <a href="{{ route('advertisers.index', ['sort_by' => 'updated_at', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}"
                       data-te-toggle="tooltip"
                       title="Click to sort results by Last Updated">Last Updated&nbsp&#9432;</a>
                </div>
            </div>

            <div class="container grid grid-cols-12 gap-4 p-2">
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
                    <div class="col-span-3">
                        {{$advertiser->updated_at}}
                    </div>
                @endforeach
            </div>
            <div class="flex justify-center mt-4">
                {{ $advertisers->links() }}
            </div>
        @else
            <p class="text-center text-xl text-gray-500">No advertisers found</p>
        @endif

        <!-- Add New Advertiser Button -->

        <div class="flex justify-center mt-4">
            <a href="{{ route('advertisers.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded">Add New Advertiser</a>
        </div>
    </div>
@endsection