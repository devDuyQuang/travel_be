@php
  $inputId    = $name;
  $removeId   = $name . '_remove';
  $caption    = $currentUrl ? basename(parse_url($currentUrl, PHP_URL_PATH)) : null;
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
    accept="image/jpeg,image/png,image/webp"
  >
  <input type="hidden" id="{{ $inputId }}_remove_flag" name="remove_{{ $name }}" value="0">
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const $el = $('#{{ $inputId }}');
  const $removeFlag = $('#{{ $inputId }}_remove_flag');
  $el.fileinput({
    showUpload: false,
    dropZoneEnabled: false,
    showClose: false,
    showRemove: true, 
    browseOnZoneClick: true,
    fileActionSettings: {
      showRemove: false,     // ẩn nút xóa trên preview
      showUpload: false,     // ẩn nút upload
      showZoom: false,        // giữ nút zoom (nếu muốn)
      showDrag: false,       // ẩn nút kéo thả
      showRotate: false,     // ẩn nút xoay
    },
    @if($currentUrl)
    // === Hiển thị ảnh hiện có như preview của plugin ===
    initialPreview: [@json($currentUrl)],
    initialPreviewAsData: true,              // xử lý URL như data để hiện ảnh
    initialPreviewConfig: [{ caption: @json($caption),  showRemove: false }],
    overwriteInitial: true,                  // hiển thị ngay trong khung “đã chọn”
    @endif
  });

  // (tuỳ chọn) nếu bạn có checkbox "Xoá ảnh hiện tại", sync với nút Remove của plugin
  $el.on('fileclear', function () {
    $removeFlag.val('1');
  });

  // Khi chọn file mới → reset flag
  $el.on('fileselect', function () {
    $removeFlag.val('0');
  });
});
</script>
