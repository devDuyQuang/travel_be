<form action="{{ panel_route('setting.home.update', ['section' => 'cta']) }}" method="POST" enctype="multipart/form-data" class="ajax-form">
  @csrf @method('PUT')
  <input type="hidden" name="enabled" value="0">
  <div class="row g-3">
    <div class="col-12"><label class="form-check form-switch"><input class="form-check-input" type="checkbox" name="enabled" value="1" @checked($enabled ?? true)><span class="form-check-label">Hiển thị section</span></label></div>
    <div class="col-12">@include('setting.home.partials.image-field', ['label' => 'Ảnh nền', 'current' => $background ?? '', 'currentName' => 'background', 'fileName' => 'background_file', 'removeName' => 'remove_background'])<div class="form-text">Khuyên dùng ảnh panorama golf/biển/điểm đến, 1920 × 900 px hoặc lớn hơn; chủ thể nên nằm lệch hai bên để chữ giữa dễ đọc. Bỏ trống để giữ ảnh template.</div></div>
    <div class="col-md-4"><label class="form-label">Dòng giới thiệu</label><input class="form-control" name="subtitle" value="{{ $subtitle ?? '' }}"></div>
    <div class="col-md-8"><label class="form-label">Tiêu đề</label><input class="form-control" name="title" value="{{ $title ?? '' }}"></div>
    <div class="col-md-6"><label class="form-label">Nhãn nút</label><input class="form-control" name="button_text" value="{{ $button_text ?? '' }}"></div>
    <div class="col-md-6"><label class="form-label">Liên kết nút</label><input class="form-control" name="button_link" value="{{ $button_link ?? '' }}"></div>
    <div class="col-12"><label class="form-label">Chữ trang trí lớn phía dưới</label><input class="form-control" name="decorative_text" value="{{ $decorative_text ?? '' }}" placeholder="EXPLORE THE WORLD"></div>
  </div>
  <div class="text-end mt-3"><button class="btn btn-primary">Lưu Banner kêu gọi hành động</button></div>
</form>
