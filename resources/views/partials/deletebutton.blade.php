<form method="POST" action="{{ $action }}" onsubmit="return confirm('{{ $confirm ?? 'Hapus data ini?' }}')">
    @csrf @method('DELETE')
    <button class="btn btn--sm btn--danger">Hapus</button>
</form>
