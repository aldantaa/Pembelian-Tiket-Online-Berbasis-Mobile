<div class="field">
    <label for="{{ $name }}">{{ $label }}</label>
    <input id="{{ $name }}" name="{{ $name }}" type="{{ $type ?? 'text' }}"
           value="{{ $value ?? old($name) }}" placeholder="{{ $placeholder ?? '' }}"
           {{ ($readonly ?? false) ? 'readonly' : '' }} required>
</div>
