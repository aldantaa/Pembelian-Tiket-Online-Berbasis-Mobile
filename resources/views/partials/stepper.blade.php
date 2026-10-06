@php $steps = ['Jadwal', 'Kursi', 'Penumpang', 'Bayar', 'E-tiket']; @endphp
<ol class="stepper">
    @foreach ($steps as $i => $step)
        <li class="{{ $i + 1 < $active ? 'is-done' : ($i + 1 === $active ? 'is-active' : '') }}">{{ $step }}</li>
    @endforeach
</ol>
