@extends('layouts.app')


@section('content')

<div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-4">{{ $header }}</h1>
        <table class="min-w-full bg-white">
            <thead class="bg-gray-500">
            <tr>
                <th class="py-2 px-4 border-b text-center text-white text-md">ID &nbsp;</th>
                <th class="py-2 px-4 border-b text-white text-md">Name &nbsp;</th>
                <th class="py-2 px-4 border-b text-center text-white text-md">Email &nbsp;</th>
                <th class="py-2 px-4 border-b text-center text-white text-md"></th>
                <th class="py-2 px-4 border-b text-center text-white text-md"></th>
            </tr>
            </thead>
                        <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td class="py-2 px-4 border-b text-center">
                                    {{ $user->id }}
                                </td>
                                <td class="py-2 px-4 border-b">
                                    {{ $user->name }}
                                </td>
                                <td class="py-2 px-4 border-b">
                                    {{ $user->email }}</td>
                                <td class="py-2 px-4 border-b">
                                    <a href="{{ route('users.edit', $user) }}" class="text-blue-500 hover:text-blue-700">Edit</a>
                                </td>
                                <td class="py-2 px-4 border-b">
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-blue-500 hover:text-blue-700" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
    <!-- Add New User Button -->
    <div class="flex justify-center mb-4 mt-10">
        <a href="{{ route('users.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-full">
            Add New User
        </a>
    </div>
</div>
@endsection