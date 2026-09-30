<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Beranda') - My Halte</title>

    {{-- CSS global --}}
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">

    {{-- CSS khusus halaman --}}
    @stack('styles')
</head>
<body>
    @include('partials.navbar')

    <main class="container">
        @include('partials.alert')
        @yield('content')
    </main>

    @include('partials.footer')

    {{-- JS khusus halaman --}}
    @stack('scripts')
</body>
</html>
