@php $benefits = collect($items ?? [])->values(); if ($benefits->isEmpty()) $benefits = collect([[], []]); @endphp
<form action="{{ panel_route('setting.home.update', ['section' => 'why_choose_us']) }}" method="POST" enctype="multipart/form-data" class="ajax-form">
  @csrf @method('PUT')
  <input type="hidden" name="enabled" value="0">
  <label class="form-check form-switch mb-3"><input class="form-check-input" type="checkbox" name="enabled" value="1" @checked($enabled ?? true)><span class="form-check-label">Hiển thị section</span></label>
  <div class="row g-3">
    <div class="col-md-4"><label class="form-label">Dòng giới thiệu</label><input class="form-control" name="subtitle" value="{{ $subtitle ?? '' }}"></div>
    <div class="col-md-8"><label class="form-label">Tiêu đề</label><input class="form-control" name="title" value="{{ $title ?? '' }}"></div>
    <div class="col-12"><label class="form-label">Mô tả</label><textarea class="form-control" rows="3" name="description">{{ $description ?? '' }}</textarea></div>
    <div class="col-md-6"><label class="form-label">Nhãn nút</label><input class="form-control" name="button_text" value="{{ $button_text ?? '' }}"></div>
    <div class="col-md-6"><label class="form-label">Liên kết nút</label><input class="form-control" name="button_link" value="{{ $button_link ?? '' }}"></div>
    <div class="col-md-6">@include('setting.home.partials.image-field', ['label' => 'Ảnh chính', 'current' => $image ?? '', 'currentName' => 'image', 'fileName' => 'image_file', 'removeName' => 'remove_image'])</div>
    <div class="col-md-6">@include('setting.home.partials.image-field', ['label' => 'Ảnh phụ', 'current' => $secondary_image ?? '', 'currentName' => 'secondary_image', 'fileName' => 'secondary_image_file', 'removeName' => 'remove_secondary_image'])</div>
    @foreach($benefits as $index => $benefit)
      <div class="col-12"><div class="border rounded p-3 row g-2">
        <div class="col-md-3"><label class="form-label">Icon</label><input class="form-control" name="items[{{ $index }}][icon]" value="{{ $benefit['icon'] ?? '' }}" placeholder="Giữ trống để dùng icon theme"></div>
        <div class="col-md-4"><label class="form-label">Tiêu đề lợi ích</label><input class="form-control" name="items[{{ $index }}][title]" value="{{ $benefit['title'] ?? '' }}"></div>
        <div class="col-md-5"><label class="form-label">Mô tả lợi ích</label><input class="form-control" name="items[{{ $index }}][description]" value="{{ $benefit['description'] ?? '' }}"></div>
      </div></div>
    @endforeach
  </div>
  <div class="text-end mt-3"><button class="btn btn-primary">Lưu Vì sao chọn Golfnity</button></div>
</form>
