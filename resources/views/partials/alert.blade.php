@if (session('success'))
    <div class="alert alert--success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert--error">{{ session('error') }}</div>
@endif
@if ($errors->any())
    <div class="alert alert--error">
        @foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach
    </div>
@endif
