<div class="mb-6">
    @if($label)
        <label class="form-label" for="{{ $name }}">{{ $label }}</label>
    @endif

    <textarea id="{{ $name }}" name="{{ $name }}" class="form-control ckeditor">{{ $value }}</textarea>

    <div class="invalid-feedback" id="error-{{ $name }}"></div>
</div>
