@extends('layouts.admin')
@section('title', 'Kelola Jadwal')
@push('styles')<link rel="stylesheet" href="{{ asset('css/pages/admin.css') }}">@endpush

@section('content')
<h1>Kelola jadwal &amp; rute</h1>

<form method="POST" class="card form-grid"
      action="{{ $editing ? route('admin.schedules.update', $editing['id']) : route('admin.schedules.store') }}">
    @csrf
    @if ($editing) @method('PUT') @endif
    <h2 class="span-all">{{ $editing ? 'Ubah jadwal #' . $editing['id'] : 'Tambah jadwal' }}</h2>
    @include('partials.input', ['label' => 'Bus', 'name' => 'bus', 'value' => $editing['bus'] ?? old('bus')])
    @include('partials.input', ['label' => 'Asal', 'name' => 'from', 'value' => $editing['from'] ?? old('from')])
    @include('partials.input', ['label' => 'Tujuan', 'name' => 'to', 'value' => $editing['to'] ?? old('to')])
    @include('partials.input', ['label' => 'Berangkat', 'name' => 'depart', 'type' => 'datetime-local', 'value' => isset($editing) ? str_replace(' ', 'T', $editing['depart']) : old('depart')])
    @include('partials.input', ['label' => 'Tiba', 'name' => 'arrive', 'type' => 'datetime-local', 'value' => isset($editing) ? str_replace(' ', 'T', $editing['arrive']) : old('arrive')])
    @include('partials.input', ['label' => 'Harga (Rp)', 'name' => 'price', 'type' => 'number', 'value' => $editing['price'] ?? old('price')])
    <div class="span-all">
        <button class="btn">{{ $editing ? 'Simpan perubahan' : 'Tambah jadwal' }}</button>
        @if ($editing)<a class="btn btn--ghost" href="{{ route('admin.schedules') }}">Batal</a>@endif
    </div>
</form>

<div class="card table-wrap">
    <table class="table">
        <thead><tr><th>Bus</th><th>Rute</th><th>Berangkat</th><th>Tiba</th><th class="num">Harga</th><th>Aksi</th></tr></thead>
        <tbody>
        @foreach ($schedules as $s)
            <tr>
                <td>{{ $s['bus'] }}</td><td>{{ $s['from'] }} → {{ $s['to'] }}</td>
                <td>{{ $s['depart'] }}</td><td>{{ $s['arrive'] }}</td>
                <td class="num">Rp {{ number_format($s['price'], 0, ',', '.') }}</td>
                <td class="row-actions">
                    <a class="btn btn--sm btn--ghost" href="{{ route('admin.schedules', ['edit' => $s['id']]) }}">Ubah</a>
                    @include('partials.deletebutton', ['action' => route('admin.schedules.delete', $s['id']), 'confirm' => 'Hapus jadwal ini?'])
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
