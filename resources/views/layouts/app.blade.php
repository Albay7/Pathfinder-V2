<!DOCTYPE html>
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
        
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        
        <!-- Flash Messages Script -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                @if(session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: '{{ session('success') }}',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                @endif

                @if(session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: '{{ session('error') }}',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#ef4444'
                    });
                @endif

                @if(session('warning'))
                    Swal.fire({
                        icon: 'warning',
                        title: 'Warning!',
                        text: '{{ session('warning') }}',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#f59e0b'
                    });
                @endif

                @if(session('info'))
                    Swal.fire({
                        icon: 'info',
                        title: 'Information',
                        text: '{{ session('info') }}',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#3b82f6'
                    });
                @endif

                // Handle validation errors
                @if($errors->any())
                    let errorMessages = [];
                    @foreach($errors->all() as $error)
                        errorMessages.push('{{ $error }}');
                    @endforeach
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        html: errorMessages.map(msg => `<div class="text-left">• ${msg}</div>`).join(''),
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#ef4444'
                    });
                @endif
            });
        </script>
    </body>
</html>
