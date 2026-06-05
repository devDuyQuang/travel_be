@props([
  'label' => null,
  'name',
  'multiple' => false,
  'currentUrl' => null,
])

@php
  $inputId = $attributes->get('id') ?: $name;
  $caption = $currentUrl ? basename(parse_url($currentUrl, PHP_URL_PATH)) : null;
@endphp

<div class="mb-6">
  @if($label)
    <label class="form-label" for="{{ $inputId }}">{{ $label }}</label>
  @endif

  <input
    id="{{ $inputId }}"
    name="{{ $name }}{{ $multiple ? '[]' : '' }}"
    type="file"
    {{ $multiple ? 'multiple' : '' }}
    accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,image/svg+xml"
    {{ $attributes->except(['id']) }}
  >

  <input
    type="hidden"
    id="{{ $inputId }}_remove_flag"
    name="remove_{{ $name }}"
    value="0"
  >
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const $el = $('#{{ $inputId }}');
  const $removeFlag = $('#{{ $inputId }}_remove_flag');

  if (!$el.length || typeof $el.fileinput !== 'function') {
    return;
  }

  if ($el.data('fileinput')) {
    $el.fileinput('destroy');
  }

  $el.fileinput({
    showUpload: false,
    dropZoneEnabled: false,
    showClose: false,
    showRemove: true,
    browseOnZoneClick: true,
    allowedFileExtensions: ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'],
    fileActionSettings: {
      showRemove: false,
      showUpload: false,
      showZoom: true,
      showDrag: false,
      showRotate: false
    },

    @if($currentUrl)
      initialPreview: [@json($currentUrl)],
      initialPreviewAsData: true,
      initialPreviewConfig: [
        {
          caption: @json($caption),
          showRemove: false
        }
      ],
      overwriteInitial: true,
    @else
      overwriteInitial: true,
    @endif
  });

  $el.on('fileclear', function () {
    $removeFlag.val('1');
  });

  $el.on('fileselect', function () {
    $removeFlag.val('0');
  });
});
</script>
@endpush