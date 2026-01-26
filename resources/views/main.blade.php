<!DOCTYPE html>
<html  dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title')</title>
        <link rel="icon" type="image/png" href="{{ asset('images/OIP.jfif') }}">

        {{-- <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet"> --}}

        <!-- Tailwind CSS -->
        <script src="{{ asset('tailwind.js') }}"></script>

        <!-- Vue.js -->
        <script src="{{ asset('vue.js') }}"></script>

        <!-- Axios -->
        <script src="{{ asset('xios.js') }}"></script>


        <!-- pusher -->
        <script src="{{ asset('pusher.js') }}"></script>

        @yield('head_content')

    </head>
    <body class="bg-slate-50 min-h-screen">
        {{-- @include('navbar')  --}}

        @yield('content')

        {{-- <p class="text-center mt-1 text-gray-400 text-sm">
            نظام التحول الرقمي السيادي &copy; 2026
        </p> --}}
        <script>


    
        </script>
    </body>
</html>
