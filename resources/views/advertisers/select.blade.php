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
            <div class="container">
                <div class="grid grid-cols-10 gap-4 bg-gray-500 p-4 text-white text-md">
                    <div class="col-span-1">Contract</div>
                    <div class="col-span-4">Business Name</div>
                    <div class="col-span-2">City</div>
                    <div class="col-span-2">Country</div>
                    <div class="col-span-1">Select</div>
                </div>

                <form action="/schedule/addSelectedAdvertisers" method="POST">
                    @csrf
                    @foreach($advertisers as $advertiser)
                        <div class="grid grid-cols-10 gap-4 p-2">
                            <div class="col-span-1">
                                {{$advertiser->contract}}
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
                                <input type="checkbox" name="advertiser_ids[]" value="{{$advertiser->id}}">
                            </div>
                        </div>
                    @endforeach
                    <div class="flex justify-between mt-4">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Add these advertisers
                        </button>
                        <a href="/schedules/{{ session('schedule_id') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>

            <div class="flex justify-center mt-4">
                {{ $advertisers->links() }}
            </div>
        @else
            <p class="text-center text-xl text-gray-500">No advertisers found</p>
        @endif
    </div>
@endsection