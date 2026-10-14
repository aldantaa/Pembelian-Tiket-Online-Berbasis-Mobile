<details class="user-menu">
    <summary>
        <span class="user-menu__avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
        <span class="user-menu__name">{{ Str::limit(auth()->user()->name, 14) }}</span>
    </summary>
    <div class="user-menu__panel">
        <small>{{ auth()->user()->email }}</small>
        <a href="{{ route('profile') }}">Profil saya</a>
        <a href="{{ route('tickets') }}">Tiket saya</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Keluar</button>
        </form>
    </div>
</details>