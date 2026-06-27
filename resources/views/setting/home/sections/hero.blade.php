@php
  $slides = collect($slides ?? [])->values();
  if ($slides->isEmpty()) $slides = collect([[]]);
@endphp
<form action="{{ panel_route('setting.home.update', ['section' => 'hero']) }}" method="POST" enctype="multipart/form-data" class="ajax-form">
  @csrf @method('PUT')
  <div id="hero-slides" class="d-grid gap-3">
    @foreach($slides as $index => $slide)
      <div class="card shadow-none border hero-slide-item">
        <div class="card-header d-flex justify-content-between align-items-center">
          <strong>
            Slide <span class="slide-number">{{ $loop->iteration }}</span>
            <span class="slide-title-summary text-muted fw-normal ms-2">
              {{ !empty($slide['title']) ? '— ' . $slide['title'] : '— Chưa có tiêu đề' }}
            </span>
          </strong>
          <div class="btn-group btn-group-sm">
            <button type="button" class="btn btn-outline-secondary move-up">↑</button>
            <button type="button" class="btn btn-outline-secondary move-down">↓</button>
            <button type="button" class="btn btn-outline-danger remove-slide">Xoá</button>
          </div>
        </div>
        <div class="card-body row g-3">
          <input type="hidden" data-field="id" name="slides[{{ $index }}][id]" value="{{ $slide['id'] ?? '' }}">
          <input type="hidden" data-field="sort" name="slides[{{ $index }}][sort]" value="{{ $slide['sort'] ?? $index }}">
          <div class="col-md-3">
            <input type="hidden" data-field="enabled" name="slides[{{ $index }}][enabled]" value="0">
            <label class="form-check form-switch mt-4">
              <input class="form-check-input" type="checkbox" data-field="enabled" name="slides[{{ $index }}][enabled]" value="1" @checked(($slide['enabled'] ?? true))>
              <span class="form-check-label">Hiển thị slide</span>
            </label>
          </div>
          <div class="col-md-9">
            @include('setting.home.partials.image-field', [
              'label' => 'Ảnh nền',
              'current' => $slide['image'] ?? '',
              'currentName' => "slides[$index][image]",
              'fileName' => "slides[$index][image_file]",
              'removeName' => "slides[$index][remove_image]",
              'currentField' => 'image',
              'fileField' => 'image_file',
              'removeField' => 'remove_image',
            ])
            <div class="form-text">Khuyên dùng ảnh ngang 1920 × 1000 px, dung lượng dưới 2 MB; giữ vùng giữa đủ tối/thoáng để đọc chữ.</div>
          </div>
          <div class="col-md-4"><label class="form-label">Dòng giới thiệu</label><input class="form-control" data-field="subtitle" name="slides[{{ $index }}][subtitle]" value="{{ $slide['subtitle'] ?? '' }}"></div>
          <div class="col-md-8"><label class="form-label">Tiêu đề chính</label><input class="form-control" data-field="title" name="slides[{{ $index }}][title]" value="{{ $slide['title'] ?? '' }}"></div>
          <div class="col-12"><label class="form-label">Mô tả</label><textarea class="form-control" rows="2" data-field="description" name="slides[{{ $index }}][description]">{{ $slide['description'] ?? '' }}</textarea></div>
          <div class="col-md-3"><label class="form-label">Dòng giá</label><input class="form-control" data-field="price_prefix" name="slides[{{ $index }}][price_prefix]" value="{{ $slide['price_prefix'] ?? '' }}"></div>
          <div class="col-md-2"><label class="form-label">Đơn vị</label><input class="form-control" data-field="price_currency" name="slides[{{ $index }}][price_currency]" value="{{ $slide['price_currency'] ?? '' }}"></div>
          <div class="col-md-2"><label class="form-label">Giá</label><input class="form-control" data-field="price" name="slides[{{ $index }}][price]" value="{{ $slide['price'] ?? '' }}"></div>
          <div class="col-md-2"><label class="form-label">Hậu tố giá</label><input class="form-control" data-field="price_suffix" name="slides[{{ $index }}][price_suffix]" value="{{ $slide['price_suffix'] ?? '' }}"></div>
          <div class="col-md-3"></div>
          <div class="col-md-6"><label class="form-label">Nhãn nút</label><input class="form-control" data-field="button_text" name="slides[{{ $index }}][button_text]" value="{{ $slide['button_text'] ?? '' }}"></div>
          <div class="col-md-6"><label class="form-label">Liên kết nút</label><input class="form-control" data-field="button_link" name="slides[{{ $index }}][button_link]" value="{{ $slide['button_link'] ?? '' }}"></div>
        </div>
      </div>
    @endforeach
  </div>
  <div class="d-flex justify-content-between mt-3">
    <button type="button" id="add-hero-slide" class="btn btn-outline-primary">Thêm slide</button>
    <button class="btn btn-primary" type="submit">Lưu Banner chính</button>
  </div>
</form>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const list = document.getElementById('hero-slides');
  const add = document.getElementById('add-hero-slide');
  if (!list || !add) return;
  function reindex() {
    list.querySelectorAll('.hero-slide-item').forEach((item, index) => {
      item.querySelector('.slide-number').textContent = index + 1;
      const title = item.querySelector('[data-field="title"]')?.value?.trim();
      const summary = item.querySelector('.slide-title-summary');
      if (summary) summary.textContent = title ? `— ${title}` : '— Chưa có tiêu đề';
      item.querySelectorAll('[data-field]').forEach(input => {
        input.name = `slides[${index}][${input.dataset.field}]`;
        if (input.dataset.field === 'sort') input.value = index;
      });
    });
  }
  list.addEventListener('click', function (event) {
    const button = event.target.closest('button');
    const item = event.target.closest('.hero-slide-item');
    if (!button || !item) return;
    if (button.classList.contains('remove-slide')) item.remove();
    if (button.classList.contains('move-up') && item.previousElementSibling) list.insertBefore(item, item.previousElementSibling);
    if (button.classList.contains('move-down') && item.nextElementSibling) list.insertBefore(item.nextElementSibling, item);
    reindex();
  });
  list.addEventListener('input', function (event) {
    if (event.target.matches('[data-field="title"]')) reindex();
  });
  add.addEventListener('click', function () {
    const template = list.querySelector('.hero-slide-item').cloneNode(true);
    template.querySelectorAll('input, textarea').forEach(input => {
      if (input.type === 'checkbox') input.checked = true;
      else if (input.dataset.field === 'remove_image') input.value = '0';
      else if (input.type !== 'hidden' || input.dataset.field !== 'enabled') input.value = '';
    });
    template.querySelectorAll('.homepage-image-preview').forEach(image => image.removeAttribute('src'));
    template.querySelectorAll('.homepage-image-preview-wrap').forEach(wrap => wrap.classList.add('d-none'));
    template.querySelectorAll('.form-text').forEach(el => el.remove());
    list.appendChild(template);
    reindex();
  });
  reindex();
});
</script>
@endpush
