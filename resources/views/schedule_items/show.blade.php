<!-- This is the view for the show site. It will display a site chosen from the site index -->
@extends('layouts.app')

@section('content')
    <div class="container max-w-max mx-auto px-4">
        <h1 class="mt-5 text-2xl">Schedule Item</h1>
        <p><strong>Associated Schedule: {{$scheduleItem->schedule_id}}</p>
        <p><strong>Start Date: </strong>{{$scheduleItem->start_date}}</p>
        <p><strong>End Date: </strong>{{$scheduleItem->end_date}}</p>
        <p><strong>Type: </strong>{{$scheduleItem->upload->resource_type}}</p>
        <p><strong>File: </strong>{{$scheduleItem->file}}</p>
        <p><strong>Created By: </strong>{{$scheduleItem->created_by}}</p>
        <hr>
        <div class="flex justify-center mt-4">
            <button onclick="history.back()" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Back</button>
            <a href="/items/{{$scheduleItem->id}}/edit" class="bg-blue-500 text-white px-4 py-2 rounded mr-2">Edit</a>
        </div>
    </div>
@endsection
