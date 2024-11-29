@extends('layouts.app')


@section('content')
    <div class="container max-w-4xl mx-auto px-4">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ isset($user) ? __('Edit User') : __('Create User') }}
        </h2>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <form method="POST" action="{{ isset($user) ? route('users.update', $user) : route('users.store') }}">
                            @csrf
                            @if(isset($user))
                                @method('PUT')
                            @endif

                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name ?? '') }}" required class="mt-1 block w-full">
                            </div>

                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email ?? '') }}" required class="mt-1 block w-full">
                            </div>

                            <div class="mb-4">
                                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                                <input type="password" name="password" id="password" class="mt-1 block w-full">
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full">
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary ml-4">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection