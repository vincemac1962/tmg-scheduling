@php use Illuminate\Support\Str; @endphp
@extends('layouts.app')

@section('content')
    <div class="max-w-max mx-auto">

        @if(count($schedules) > 0)

            <div class="container grid grid-cols-10 gap-4 bg-gray-500 p-4">
                <div class="text-white text-md col-span-1">
                    ID
                </div>
                <div class="text-white text-md col-span-5">
                    Title
                </div>
                <div class="text-white text-md col-span-2">Last Modified</div>
                <div class="text-white text-md col-span-2">Created On</div>
            </div>

            <div class="container grid grid-cols-10 gap-4 p-2">

                @foreach($schedules as $schedule)
                    <div class="col-span-1">
                        <a href="/schedules/{{$schedule->id}}"
                           class="text-blue-500 hover:text-blue-700">{{$schedule->id}}</a>
                    </div>
                    <div class="col-span-5">
                        {{ Str::limit($schedule->title, 50) }}
                    </div>
                    <div class="col-span-2">
                        {{$schedule->updated_at->format('d/m/Y H:i') }}
                    </div>
                    <div class="col-span-2">
                        {{$schedule->created_at->format('d/m/Y H:i')}}
                    </div>

                @endforeach
            </div>
            <div class="flex justify-center mt-4">
                {{ $schedules->links() }}
            </div>
        @else
            <p class="text-center text-xl text-gray-500">No sites found</p>
        @endif
    </div>
@endsection