@props([
  'label' => null,
  'name',
  'multiple' => false,
  'currentUrl' => null,
  'removeName' => null,
  'help' => 'JPG, PNG, WEBP, GIF hoặc SVG. Tối đa 5 MB.',
])

@php
  $inputId = $attributes->get('id') ?: 'media-' . \Illuminate\Support\Str::slug($name);
  $removeField = $removeName ?: 'remove_' . str_replace(['[', ']'], ['_', ''], $name);
@endphp

<div class="cms-media-field mb-4" data-media-field>
  @if($label)
    <label class="form-label" for="{{ $inputId }}">{{ $label }}</label>
  @endif

  <div class="cms-media-preview {{ $currentUrl ? '' : 'is-empty' }}" data-media-preview>
    <img
      src="{{ $currentUrl ?: '' }}"
      alt="{{ $label ?: 'Ảnh xem trước' }}"
      data-media-image
      @if(!$currentUrl) hidden @endif
    >
    <div class="cms-media-placeholder" data-media-placeholder @if($currentUrl) hidden @endif>
      <span class="material-icons-outlined">image</span>
      <span>Chưa có hình ảnh</span>
    </div>
  </div>

  <input
    id="{{ $inputId }}"
    name="{{ $name }}{{ $multiple ? '[]' : '' }}"
    type="file"
    class="form-control"
    {{ $multiple ? 'multiple' : '' }}
    accept="image/jpeg,image/png,image/gif,image/webp,image/svg+xml"
    {{ $attributes->except(['id', 'class']) }}
    data-media-input
  >

  @unless($multiple)
    <input type="hidden" name="{{ $removeField }}" value="0" data-media-remove-flag>
    <button
      type="button"
      class="btn btn-outline-danger btn-sm mt-2 {{ $currentUrl ? '' : 'd-none' }}"
      data-media-remove
    >
      <span class="material-icons-outlined">delete</span>
      Xóa ảnh
    </button>
  @endunless

  @if($help)
    <div class="form-text">{{ $help }}</div>
  @endif

  <div class="form-text text-danger d-none" data-media-remove-note>
    Ảnh sẽ được xóa khỏi hệ thống khi bạn bấm nút lưu.
  </div>

  @error($name)
    <div class="invalid-feedback d-block">{{ $message }}</div>
  @enderror
</div>

@once
  @push('scripts')
    <script>
      document.addEventListener('change', function (event) {
        const input = event.target.closest('[data-media-input]');
        if (!input || input.multiple) return;

        const field = input.closest('[data-media-field]');
        const file = input.files && input.files[0];
        if (!field || !file) return;

        const image = field.querySelector('[data-media-image]');
        const placeholder = field.querySelector('[data-media-placeholder]');
        const removeButton = field.querySelector('[data-media-remove]');
        const removeFlag = field.querySelector('[data-media-remove-flag]');
        const removeNote = field.querySelector('[data-media-remove-note]');

        if (image) {
          image.src = URL.createObjectURL(file);
          image.hidden = false;
        }
        if (placeholder) placeholder.hidden = true;
        if (removeButton) removeButton.classList.remove('d-none');
        if (removeFlag) removeFlag.value = '0';
        if (removeNote) removeNote.classList.add('d-none');
        field.querySelector('[data-media-preview]')?.classList.remove('is-empty');
      });

      document.addEventListener('click', function (event) {
        const button = event.target.closest('[data-media-remove]');
        if (!button) return;

        const field = button.closest('[data-media-field]');
        if (!field) return;

        const input = field.querySelector('[data-media-input]');
        const image = field.querySelector('[data-media-image]');
        const placeholder = field.querySelector('[data-media-placeholder]');
        const removeFlag = field.querySelector('[data-media-remove-flag]');
        const removeNote = field.querySelector('[data-media-remove-note]');

        if (input) input.value = '';
        if (image) {
          image.removeAttribute('src');
          image.hidden = true;
        }
        if (placeholder) placeholder.hidden = false;
        if (removeFlag) removeFlag.value = '1';
        if (removeNote) removeNote.classList.remove('d-none');
        button.classList.add('d-none');
        field.querySelector('[data-media-preview]')?.classList.add('is-empty');
      });
    </script>
  @endpush
@endonce
