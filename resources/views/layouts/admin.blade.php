<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin My Halte') - My Halte</title>

    {{-- CSS global, sama seperti layouts.master --}}
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">

    {{-- CSS khusus admin --}}
    @stack('styles')
</head>

<body>
    @include('partials.adminnavbar')

    <main class="container">
        @include('partials.alert')
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
