<!DOCTYPE html>
<!--suppress JSUnusedLocalSymbols -->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif
            @if(session('success'))
                <div class="alert-success bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert" id="alert-success">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="alert-error bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert" id="alert-error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

                        <!-- Page Content -->
            <main>
                <div class="bg-white rounded-lg m-8 p-4">
                    @yield('content')
                </div>
            </main>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', (event) => {
                // Set the timeout to 5 seconds (5000 milliseconds)
                setTimeout(function() {
                    // Select the success and error messages by class
                    const successMessage = document.querySelector('.alert-success');
                    const errorMessage = document.querySelector('.alert-error');

                    // Check if the success message exists and hide it
                    if (successMessage) {
                        successMessage.style.display = 'none';
                    }

                    // Check if the error message exists and hide it
                    if (errorMessage) {
                        errorMessage.style.display = 'none';
                    }
                }, 5000); // 5000 milliseconds = 5 seconds
            });
        </script>
    </body>
</html>
