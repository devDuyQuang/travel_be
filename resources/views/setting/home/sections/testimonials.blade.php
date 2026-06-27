@php $reviews = collect($items ?? [])->values(); if ($reviews->isEmpty()) $reviews = collect([[]]); @endphp
<form action="{{ panel_route('setting.home.update', ['section' => 'testimonials']) }}" method="POST" enctype="multipart/form-data" class="ajax-form">
  @csrf @method('PUT')
  <input type="hidden" name="enabled" value="0">
  <div class="row g-3 mb-3">
    <div class="col-12"><label class="form-check form-switch"><input class="form-check-input" type="checkbox" name="enabled" value="1" @checked($enabled ?? true)><span class="form-check-label">Hiển thị section</span></label></div>
    <div class="col-md-4"><label class="form-label">Tiêu đề phụ</label><input class="form-control" name="subtitle" value="{{ $subtitle ?? '' }}"></div>
    <div class="col-md-8"><label class="form-label">Tiêu đề chính</label><input class="form-control" name="title" value="{{ $title ?? $main_title ?? '' }}"></div>
    <div class="col-12"><label class="form-label">Mô tả</label><textarea class="form-control" rows="2" name="description">{{ $description ?? '' }}</textarea></div>
  </div>
  <div id="testimonial-items" class="d-grid gap-3">
    @foreach($reviews as $index => $review)
      <div class="border rounded p-3 row g-2 testimonial-item">
        <input type="hidden" data-field="enabled" name="items[{{ $index }}][enabled]" value="0">
        <div class="col-12 text-end"><div class="btn-group btn-group-sm"><button type="button" class="btn btn-outline-secondary move-up">↑</button><button type="button" class="btn btn-outline-secondary move-down">↓</button><button type="button" class="btn btn-outline-danger remove-review">Xoá</button></div></div>
        <div class="col-md-2"><label class="form-check mt-4"><input class="form-check-input" type="checkbox" data-field="enabled" name="items[{{ $index }}][enabled]" value="1" @checked($review['enabled'] ?? true)><span class="form-check-label">Hiển thị</span></label></div>
        <div class="col-md-2"><label class="form-label">Thứ tự</label><input class="form-control" type="number" min="0" data-field="sort" name="items[{{ $index }}][sort]" value="{{ $review['sort'] ?? $index }}"></div>
        <div class="col-md-4"><label class="form-label">Tên khách hàng</label><input class="form-control" data-field="name" name="items[{{ $index }}][name]" value="{{ $review['name'] ?? '' }}"></div>
        <div class="col-md-4"><label class="form-label">Chức vụ</label><input class="form-control" data-field="role" name="items[{{ $index }}][role]" value="{{ $review['role'] ?? $review['position'] ?? '' }}"></div>
        <div class="col-md-8"><label class="form-label">Nội dung</label><textarea class="form-control" rows="2" data-field="content" name="items[{{ $index }}][content]">{{ $review['content'] ?? $review['description'] ?? '' }}</textarea></div>
        <div class="col-md-2"><label class="form-label">Số sao</label><input class="form-control" type="number" min="1" max="5" data-field="rating" name="items[{{ $index }}][rating]" value="{{ $review['rating'] ?? 5 }}"></div>
        <div class="col-md-2">
          @include('setting.home.partials.image-field', [
            'label' => 'Ảnh',
            'current' => $review['image'] ?? '',
            'currentName' => "items[$index][image]",
            'fileName' => "items[$index][image_file]",
            'removeName' => "items[$index][remove_image]",
            'currentField' => 'image',
            'fileField' => 'image_file',
            'removeField' => 'remove_image',
          ])
        </div>
      </div>
    @endforeach
  </div>
  <div class="d-flex justify-content-between mt-3"><button type="button" id="add-testimonial" class="btn btn-outline-primary">Thêm phản hồi</button><button class="btn btn-primary">Lưu Ý kiến khách hàng</button></div>
</form>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const list = document.getElementById('testimonial-items');
  const add = document.getElementById('add-testimonial');
  if (!list || !add) return;
  function reindex() {
    list.querySelectorAll('.testimonial-item').forEach((item, index) => {
      item.querySelectorAll('[data-field]').forEach(input => {
        input.name = `items[${index}][${input.dataset.field}]`;
        if (input.dataset.field === 'sort') input.value = index;
      });
    });
  }
  list.addEventListener('click', function (event) {
    const button = event.target.closest('button');
    const item = event.target.closest('.testimonial-item');
    if (!button || !item) return;
    if (button.classList.contains('remove-review')) item.remove();
    if (button.classList.contains('move-up') && item.previousElementSibling) list.insertBefore(item, item.previousElementSibling);
    if (button.classList.contains('move-down') && item.nextElementSibling) list.insertBefore(item.nextElementSibling, item);
    reindex();
  });
  add.addEventListener('click', function () {
    const item = list.querySelector('.testimonial-item').cloneNode(true);
    item.querySelectorAll('input, textarea').forEach(input => {
      if (input.type === 'checkbox') input.checked = true;
      else if (input.dataset.field === 'rating') input.value = 5;
      else if (input.dataset.field === 'remove_image') input.value = 0;
      else input.value = '';
    });
    item.querySelectorAll('.homepage-image-preview').forEach(image => image.removeAttribute('src'));
    item.querySelectorAll('.homepage-image-preview-wrap').forEach(wrap => wrap.classList.add('d-none'));
    list.appendChild(item);
    reindex();
  });
  reindex();
});
</script>
@endpush
