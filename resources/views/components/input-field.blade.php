<div class="mb-6">
    <label class="form-label" for="{{ $name }}">
        {{ $label }} 
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>

    <input 
        type="{{ $type }}" 
        name="{{ $name }}" 
        id="{{ $name }}" 
        class="form-control @error($name) is-invalid @enderror" 
        value="{{ old($name, $value) }}" 
        @if($required) required @endif
    />

    <div class="invalid-feedback" id="error-{{ $name }}">
        @error($name) {{ $message }} @enderror
    </div>
</div>
