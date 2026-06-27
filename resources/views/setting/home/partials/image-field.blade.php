@php
  $current = $current ?? '';
  $previewUrl = '';
  if ($current) {
      $previewUrl = \Illuminate\Support\Str::startsWith($current, ['http://', 'https://'])
          ? $current
          : asset('storage/' . ltrim(\Illuminate\Support\Str::after($current, 'storage/'), '/'));
  }
  $fieldAttributes = fn($field) => !empty($field) ? ' data-field="' . e($field) . '"' : '';
@endphp

<div class="homepage-image-field" data-current-url="{{ $previewUrl }}">
  <label class="form-label">{{ $label }}</label>
  <input type="hidden" name="{{ $currentName }}" value="{{ $current }}"{!! $fieldAttributes($currentField ?? null) !!}>
  <input type="hidden" name="{{ $removeName }}" value="0" class="homepage-image-remove-flag"{!! $fieldAttributes($removeField ?? null) !!}>
  <input class="form-control homepage-image-input" type="file" accept="image/*" name="{{ $fileName }}"{!! $fieldAttributes($fileField ?? null) !!}>

  <div class="homepage-image-preview-wrap mt-2 {{ $previewUrl ? '' : 'd-none' }}">
    <img
      class="homepage-image-preview rounded border"
      src="{{ $previewUrl }}"
      alt="{{ $label }}"
      style="display:block;max-width:100%;width:auto;max-height:180px;object-fit:cover"
    >
    <button type="button" class="btn btn-sm btn-outline-danger homepage-image-remove mt-2">
      <i class="ti tabler-trash me-1"></i>Xoá ảnh
    </button>
  </div>
</div>
