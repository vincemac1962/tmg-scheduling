@extends('layouts.app')

@section('content')
    <div class="max-w-max mx-auto">

        <!-- Form for sorting and filtering -->
        <form method="GET" action="{{ route('sites.index') }}">
            <div class="flex items-center p-4">
                <div>
                    <label for="filter">Filter by Site Ref or Name:</label>
                    <input type="text" id="filter" name="filter" value="{{ request('filter') }}">
                </div>

                <div>
                    <button type="submit" class="ml-5">Apply</button>
                </div>
                @if(request('filter'))
                    <div>
                        <a href="{{ route('sites.index') }}" class="ml-5 text-red-600">Reset Filter</a>
                    </div>
                @endif
            </div>
        </form>

        @if(count($sites) > 0)

            <div class="container grid grid-cols-10 gap-4 bg-gray-500 p-4">
                <div class="text-white text-md col-span-1">
                    <a href="{{ route('sites.index', ['sort_by' => 'site_ref', 'direction' => request('sort_by') == 'site_ref' && request('direction') == 'asc' ? 'desc' : 'asc', 'filter' => request('filter')]) }}" class="transititext-primary text-primary transition duration-150 ease-in-out hover:text-primary-600 focus:text-primary-600 active:text-primary-700 dark:text-primary-400 dark:hover:text-primary-500 dark:focus:text-primary-500 dark:active:text-primary-600"
                       data-te-toggle="tooltip"
                       title="Click to sort results by Site Reference">Site Ref&nbsp;&#9432;</a>
                </div>
                <div class="text-white text-md col-span-2">
                    <a href="{{ route('sites.index', ['sort_by' => 'site_name', 'direction' => request('sort_by') == 'site_name' && request('direction') == 'asc' ? 'desc' : 'asc', 'filter' => request('filter')]) }}" class="transititext-primary text-primary transition duration-150 ease-in-out hover:text-primary-600 focus:text-primary-600 active:text-primary-700 dark:text-primary-400 dark:hover:text-primary-500 dark:focus:text-primary-500 dark:active:text-primary-600"
                       data-te-toggle="tooltip"
                       title="Click to sort results by Site Name">Site Name&nbsp;&#9432;</a>
                </div>
                <div class="text-white text-md col-span-5">Site Address</div>
                <div class="text-white text-md col-span-1">Status</div>
                <div class="text-white text-md col-span-1">Select</div>
            </div>
            <form action="{{ route('schedules.associateSites', $schedule_id) }}" method="POST">
            <div class="container grid grid-cols-10 gap-4 p-2">

                        @csrf
                        @foreach($sites as $site)
                            <div class="col-span-1">
                                {{$site->site_ref}}
                            </div>
                            <div class="col-span-2">
                                {{$site->site_name}}
                            </div>
                            <div class="col-span-5">
                                {{$site->site_address}}
                            </div>
                            <div class="col-span-1">
                                    <span style="color: {{$site->site_active ? 'green' : 'red'}}">
                                        {{$site->site_active ? 'Active' : 'Inactive'}}
                                    </span>
                            </div>
                            <div class="col-span-1">
                            <label for ="sites[]"></label>
                            <input type="checkbox" id="sites[]" name="sites[]" value="{{ $site->id }}">
                            </div>
                    @endforeach

                </div>
                <div class="flex justify-center mt-4">
                    <a href="/schedules/{{ $schedule_id }}" class="w-40 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded mr-2 text-center text-xs">Back</a>
                    <button type="submit" class="w-40 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded mr-2 text-center text-xs">Associate Sites</button>
                </div>
            </form>
                    <div class="flex justify-center mt-4">
                        {{ $sites->links() }}
                    </div>
        @else
            <p class="text-center text-xl text-gray-500">No sites found</p>
        @endif
    </div>
@endsection