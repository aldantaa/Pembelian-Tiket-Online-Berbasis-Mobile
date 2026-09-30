<header class="navbar">
    <div class="navbar__inner">
        @include('partials.logo')
        <nav class="navbar__menu">
            <a href="{{ route('schedules') }}" class="{{ request()->routeIs('schedules') ? 'is-active' : '' }}">Cari tiket</a>
            <a href="{{ route('tickets') }}" class="{{ request()->routeIs('tickets') ? 'is-active' : '' }}">Tiket saya</a>

            @if (session()->has('user'))
                @include('partials.user-menu')
            @else
                <a href="{{ route('login') }}" class="btn btn--sm">Masuk</a>
            @endif
        </nav>
    </div>
</header>
