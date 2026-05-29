<div class="mb-6">
    <label class="form-label" for="{{ $name }}">
        {{ $label }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>

    <textarea 
        name="{{ $name }}" 
        id="{{ $name }}" 
        rows="{{ $rows }}" 
        class="form-control @error($name) is-invalid @enderror"
        @if($required) required @endif
    >{{ old($name, $value) }}</textarea>

    <div class="invalid-feedback" id="error-{{ $name }}">
        @error($name) {{ $message }} @enderror
    </div>
</div>
