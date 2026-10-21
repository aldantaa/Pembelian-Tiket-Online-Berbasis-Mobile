<header class="navbar">
    <div class="navbar__inner">
        @include('partials.logo')

        <nav class="navbar__menu">
            @guest
                @if (!request()->routeIs('login', 'register'))
                    <a href="{{ route('login') }}" class="btn btn--sm">
                        Masuk
                    </a>
                @endif
            @else
                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}"
                       class="{{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
                        Dashboard
                    </a>

                    <a href="{{ route('admin.schedules') }}">Kelola Jadwal</a>
                    <a href="{{ route('admin.users') }}">Kelola Pengguna</a>
                    <a href="{{ route('admin.transactions') }}">Transaksi</a>
                    <a href="{{ route('admin.orders') }}">Riwayat Pesanan</a>
                    <a href="{{ route('admin.alerts') }}">Notifikasi</a>

                @else
                    <a href="{{ route('schedules') }}"
                       class="{{ request()->routeIs('schedules') ? 'is-active' : '' }}">
                        Cari tiket
                    </a>

                    <a href="{{ route('tickets.index') }}">Tiket saya</a>
                    <a href="{{ route('profile') }}">Profil saya</a>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn--sm">Keluar</button>
                </form>
            @endguest
        </nav>
    </div>
</header>
