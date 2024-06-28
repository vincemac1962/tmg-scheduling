<!-- This is the view for the show site. It will display a site chosen from the site index -->
@extends('layouts.app')

@section('content')
    <div class="container max-w-max mx-auto px-4">
        <h1 class="mt-5 text-2xl">Site</h1>
        <h3 class="mt-5 text-xl">{{$site->site_ref}} - {{$site->site_name}}</h3>
        <p><strong>Site Active: </strong><span class="text-{{ $site->site_active ? 'green' : 'red' }}">{{ $site->site_active ? 'True' : 'False' }}</span></p>
        <p><strong>Last Updated: </strong>{{$site->site_last_updated}}</p>
        <p><strong>Address: </strong>{{$site->site_address}}</p>
        <p><strong>Postcode: </strong>{{$site->site_postcode}}</p>
        <p><strong>Phone: </strong>{{$site->site_tel}}</p>
        <p><strong>POC: </strong>{{$site->site_contact}}</p>
        <p><strong>Email: </strong>{{$site->site_email}}</p>

        <p class="w-1/3 break-words"><strong>Notes: </strong>{{$site->site_notes}}</p>
        <hr>
        <div class="flex justify-center mt-4">
            <a href="/sites" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Back</a>
            <a href="/sites/{{$site->id}}/edit" class="bg-blue-500 text-white px-4 py-2 rounded mr-2">Edit</a>
            <form action="{{ route('sites.destroy', $site->id) }}" method="POST" class="mr-2">
                @csrf
                <input type="hidden" name="_method" value="DELETE">
                <input type="submit" value="Delete" class="bg-red-500 text-white px-4 py-2 rounded cursor-pointer">
            </form>
        </div>
    </div>
@endsection
