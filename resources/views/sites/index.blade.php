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

            <div class="container grid grid-cols-9 gap-4 bg-gray-500 p-4">
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
            </div>

            <div class="container grid grid-cols-9 gap-4 p-2">


                    @foreach($sites as $site)
                        <div class="col-span-1">
                            <a href="/sites/{{$site->id}}" class="text-blue-500 hover:text-blue-700">{{$site->site_ref}}</a>
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
                    @endforeach
                </div>
                    <div class="flex justify-center mt-4">
                        {{ $sites->links() }}
                    </div>
        @else
            <p class="text-center text-xl text-gray-500">No sites found</p>
        @endif
    </div>
@endsection