<header class="navbar">
    <div class="navbar__inner">
        {{-- Logo My Halte --}}
        @include('partials.logo')

        {{-- Navigasi Admin --}}
        <nav class="navbar__menu">
            <a href="{{ route('admin.dashboard') }}"
               class="{{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
                Dashboard
            </a>

            <a href="{{ route('admin.schedules') }}"
               class="{{ request()->routeIs('admin.schedules*') ? 'is-active' : '' }}">
                Kelola Jadwal
            </a>

            <a href="{{ route('admin.users') }}"
               class="{{ request()->routeIs('admin.users*', 'admin.user') ? 'is-active' : '' }}">
                Kelola Pengguna
            </a>

            <a href="{{ route('admin.transactions') }}"
               class="{{ request()->routeIs('admin.transactions') ? 'is-active' : '' }}">
                Transaksi
            </a>

            <a href="{{ route('admin.orders') }}"
               class="{{ request()->routeIs('admin.orders') ? 'is-active' : '' }}">
                Riwayat Pesanan
            </a>

            <a href="{{ route('admin.alerts') }}"
               class="{{ request()->routeIs('admin.alerts') ? 'is-active' : '' }}">
                Notifikasi
            </a>

            {{-- Dropdown Profil --}}
            <div class="profile-menu">
                <button type="button"
                        class="profile-trigger"
                        onclick="document.getElementById('profileDropdown').classList.toggle('show')">
                    <span class="profile-avatar">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </span>
                    <span>{{ auth()->user()->name ?? 'Administrator' }}</span>
                </button>

                <div id="profileDropdown" class="profile-dropdown">
                    <div class="profile-email">
                        {{ auth()->user()->email ?? 'Administrator' }}
                    </div>

                    <a href="{{ route('profile') }}">Profil Saya</a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="logout-button">Keluar</button>
                    </form>
                </div>
            </div>
        </nav>
    </div>
</header>

<style>
.profile-menu {
    position: relative;
    flex-shrink: 0;
}

.profile-trigger {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 6px;
    border: 0;
    background: transparent;
    cursor: pointer;
    color: inherit;
    white-space: nowrap;
}

.profile-avatar {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    flex-shrink: 0;
    border-radius: 50%;
    background: #2454e8;
    color: white;
}

.profile-dropdown {
    display: none;
    position: absolute;
    right: 0;
    top: calc(100% + 12px);
    min-width: 190px;
    padding: 12px;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    box-shadow: 0 8px 24px #00000018;
    z-index: 1000;
}

.profile-dropdown.show {
    display: block;
}

.profile-email {
    padding: 8px;
    border-bottom: 1px solid #e2e8f0;
    font-size: 13px;
    color: #64748b;
    overflow-wrap: anywhere;
}

.profile-dropdown a,
.logout-button {
    display: block;
    width: 100%;
    box-sizing: border-box;
    padding: 10px 8px;
    border: 0;
    background: transparent;
    text-align: left;
    text-decoration: none;
    color: #475569;
    cursor: pointer;
}

.logout-button {
    color: #ef4444;
}
</style>
