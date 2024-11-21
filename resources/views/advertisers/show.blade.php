@extends('layouts.app')

@section('content')
    <!- display the advertiser details ->
    <div class="container max-w-max mx-auto px-4">
        <h1 class="mt-5 text-2xl">Advertiser</h1>
        <p><strong>ID: </strong>{{$advertiser->id}}</p>
        <p><strong>Contract: </strong>{{$advertiser->contract}}</p>
        <p><strong>Business Name: </strong>{{$advertiser->business_name}}</p>
        <p><strong>City: </strong>{{$advertiser->city}}</p>
        <p><strong>Country: </strong>{{$advertiser->country}}</p>
        <p><strong>Created By: </strong>{{$advertiser->creator->name}}</p>
        <p><strong>Last Modified: </strong>{{$advertiser->updated_at->format('d/m/Y H:i') }}</p>
        <hr>
        <div class="flex justify-center mt-4">
            <a href="javascript:void(0)" onclick="history.back()" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Back</a>
            <a href="/advertisers/{{$advertiser->id}}/edit" class="bg-blue-500 text-white px-4 py-2 rounded mr-2">Edit</a>
            <form action="{{ route('advertisers.destroy', $advertiser->id) }}" method="POST" class="mr-2">
                @csrf
                <input type="hidden" name="_method" value="DELETE">
                <input type="submit" value="Delete" class="bg-red-500 text-white px-4 py-2 rounded cursor-pointer">
            </form>
        </div>
    </div>
@endsection

