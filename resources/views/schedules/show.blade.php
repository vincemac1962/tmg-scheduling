@php use Illuminate\Support\Str; @endphp
        <!-- This is the view for the show schedule. It will display a schedule chosen from the schedule index -->
@extends('layouts.app')

@section('content')
    <div class="container max-w-max mx-auto mb-16 px-4">
        <h1 class="mt-5 text-2xl">Schedule</h1>
        <p><strong>ID: </strong>{{$schedule->id}}</p>
        <p><strong>Title: </strong>{{$schedule->title}}</p>
        <p style="word-wrap: break-word"><strong>Description: </strong>{{ $schedule->description }}</p>
        <p><strong>Created By: </strong>{{$schedule->site_id}}</p>
        <p><strong>Last Modified: </strong>{{$schedule->updated_at->format('d/m/Y H:i') }}</p>
        <p><strong>Created: </strong>{{$schedule->created_at->format('d/m/Y H:i') }}</p>
        @if(count($schedule->scheduleItems) > 0)
            <div class="container grid grid-cols-8 gap-4 bg-gray-500 p-2 mt-5">
                <div class="text-white text-md col-span-1">Type</div>
                <div class="text-white text-md col-span-3">
                    File
                </div>
                <div class="text-white text-md col-span-1">Last Modified</div>
                <div class="text-white text-md col-span-1">Created On</div>
                <div class="text-white text-md col-span-1"></div>
                <div class="text-white text-md col-span-1"></div>
            </div>
            <div class="container grid grid-cols-8 gap-4 p-4">
            @foreach($scheduleItems as $item)
                <div class="col-span-1">
                    {{  $item->upload->resource_type  }}
                </div>
                <div class="col-span-3">
                    <a href="{{ Storage::url($item->file) }}">
                        {{ Str::limit($item->file, 50) }}
                    </a>
                </div>
                <div class="col-span-1">
                    {{ Carbon\Carbon::parse($item->start_date)->format('d-m-Y') }}
                </div>
                <div class="col-span-1">
                    {{ Carbon\Carbon::parse($item->end_date)->format('d-m-Y') }}
                </div>
                <div class="col-span-1">
                    <a href="/schedule_items/{{$item->id}}"
                       class="text-blue-500 hover:text-blue-700">View Item</a>
                </div>
                    <div class="col-span-1">
                        <form action="/schedule_items/{{ $item->id }}" method="POST" id="delete-form-{{ $item->id }}" style="display: none;">
                            @csrf
                            <input type="hidden" name="_method" value="DELETE">
                        </form>
                        <a href="javascript:void(0);" onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this item?')) { document.getElementById('delete-form-{{ $item->id }}').submit(); }" class="text-blue-500 hover:text-blue-700">Delete Item</a>
                    </div>
            @endforeach
        @else
                <p class="text-center text-xl text-gray-500">No schedule items found</p>
        @endif
        <hr>
        </div>
        <div class="flex justify-center">
                {{ $scheduleItems->links() }}
        </div>
            <div class="flex justify-center mt-4">
                <a href="/schedules" class="w-40 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded mr-2 text-center text-xs">Back</a>
                <a href="/schedules/{{$schedule->id}}/edit" class="w-40 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded mr-2 text-center text-xs">Edit Schedule</a>
                <a href="/schedule_items/create?schedule_id={{$schedule->id}}" class="w-40 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded mr-2 text-center text-xs">Add Item</a>
                <button type="button" id="openModal" class="w-40 bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded mr-2 text-center text-xs" data-toggle="modal" data-target="#chooseAdvertiserModal">Add/Choose Advertiser</button>
                <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST" class="mr-2">
                    @csrf
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="submit" value="Delete" class="w-40 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded cursor-pointer text-center text-xs">
                </form>
            </div>

            <!-- Modal -->
            <div id="chooseAdvertiserModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                    <!-- Modal content -->
                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                        Choose Action
                                    </h3>
                                    <div class="mt-2">
                                        <button id="addNewAdvertiser" onclick="redirectToAdvertiserAction({{ $schedule->id }}, 'create')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            Add New Advertiser
                                        </button>
                                        <button id="selectExistingAdvertiser" onclick="window.location.href='/advertisers/select'" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                            Select Existing Advertiser
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="button" id="closeModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-xs">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.getElementById('openModal').addEventListener('click', function() {
                    document.getElementById('chooseAdvertiserModal').classList.remove('hidden');
                });

                document.getElementById('closeModal').addEventListener('click', function() {
                    document.getElementById('chooseAdvertiserModal').classList.add('hidden');
                });

                // Optional: Close modal if clicking outside of it
                window.addEventListener('click', function(event) {
                    let modal = document.getElementById('chooseAdvertiserModal');
                    if (event.target === modal) {
                        modal.classList.add('hidden');
                    }
                });

                function redirectToAdvertiserAction(scheduleId, action) {
                    let baseUrl = action === 'create' ? '/advertisers/create' : '/advertisers';
                    window.location.href = `${baseUrl}?schedule_id=${scheduleId}`;
                }
            </script>



@endsection
