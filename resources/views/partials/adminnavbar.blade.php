<header class="navbar">
    <div class="navbar__inner">
        @include('partials.logo')

        <nav class="navbar__menu">
            <a href="{{ route('admin.dashboard') }}"
               class="{{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
                Laporan
            </a>

            <a href="{{ route('admin.users') }}"
               class="{{ request()->routeIs('admin.users') ? 'is-active' : '' }}">
                Pengguna
            </a>

            <a href="{{ route('admin.transactions') }}"
               class="{{ request()->routeIs('admin.transactions') ? 'is-active' : '' }}">
                Transaksi
            </a>

            <a href="{{ route('admin.schedules') }}"
               class="{{ request()->routeIs('admin.schedules') ? 'is-active' : '' }}">
                Jadwal
            </a>

           
@auth
    <a href="{{ route('profile') }}" class="navbar__user">
        <span class="navbar__avatar">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </span>
        <span>{{ auth()->user()->name }}</span>
    </a>
@endauth

        </nav>
    </div>
</header>
