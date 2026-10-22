@extends('layouts.admin')

@section('title', 'Kelola Pengguna')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/admin.css') }}">
@endpush

@section('content')
<h1>Data pengguna</h1>

@if ($editing)
    <form method="POST" action="{{ route('admin.users.update', $editing->id) }}" class="card form-inline">
        @csrf
        @method('PUT')

        <h2>Ubah pengguna #{{ $editing->id }}</h2>

        @include('partials.input', [
            'label' => 'Nama',
            'name' => 'name',
            'value' => $editing->name
        ])

        @include('partials.input', [
            'label' => 'Email',
            'name' => 'email',
            'type' => 'email',
            'value' => $editing->email
        ])

        <button type="submit" class="btn">Simpan</button>
        <a class="btn btn--ghost" href="{{ route('admin.users') }}">Batal</a>
    </form>
@endif

<div class="card table-wrap">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Terdaftar</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($users as $u)
                <tr>
                    <td>{{ $u->id }}</td>
                    <td>{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td>
                        {{ $u->created_at
                            ? \Carbon\Carbon::parse($u->created_at)->format('d M Y')
                            : '-' }}
                    </td>
                    <td class="row-actions">
                        <a
                            class="btn btn--sm btn--ghost"
                            href="{{ route('admin.users', ['edit' => $u->id]) }}"
                        >Ubah</a>

                        @include('partials.deletebutton', [
                            'action' => route('admin.users.delete', $u->id),
                            'confirm' => 'Hapus pengguna ini?'
                        ])
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="empty">Belum ada pengguna.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
