@props(['class' => 'form-check-input'])

<div class="mb-6 form-check form-switch">
    {{-- hidden để gửi 0 khi không check --}}
    <input type="hidden" name="{{ $name }}" value="0">

    <input
        type="checkbox"
        id="{{ $id }}"
        name="{{ $name }}"
        value="{{ $value }}"
        {{ old($name, $checked ?? 0) == $value ? 'checked' : '' }}
        {{ $attributes->merge(['class' => $class]) }}
    >

    @if($label)
        <label class="form-check-label" for="{{ $id }}">{{ $label }}</label>
    @endif

    @error($name)
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>
