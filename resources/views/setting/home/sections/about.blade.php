<form action="{{ panel_route('setting.home.update', ['section' => 'about']) }}" method="POST" enctype="multipart/form-data" class="ajax-form">
  @csrf @method('PUT')
  <div class="row g-3">
    <div class="col-md-4"><label class="form-label">Dòng giới thiệu</label><input class="form-control" name="subtitle" value="{{ $subtitle ?? '' }}"></div>
    <div class="col-md-8"><label class="form-label">Tiêu đề</label><input class="form-control" name="title" value="{{ $title ?? '' }}"></div>
    <div class="col-12"><label class="form-label">Mô tả</label><textarea class="form-control" rows="3" name="description">{{ $description ?? '' }}</textarea></div>
    <div class="col-md-6"><label class="form-label">Nhãn nút</label><input class="form-control" name="button_text" value="{{ $button_text ?? '' }}"></div>
    <div class="col-md-6"><label class="form-label">Liên kết nút</label><input class="form-control" name="button_link" value="{{ $button_link ?? '' }}"></div>
    <div class="col-12">
      @include('setting.home.partials.image-field', [
        'label' => 'Logo giữa section (thay logo Tripion của theme)',
        'current' => $logo ?? '',
        'currentName' => 'logo',
        'fileName' => 'logo_file',
        'removeName' => 'remove_logo',
      ])
      <div class="form-text">Khuyên dùng PNG/WebP nền trong suốt, khoảng 360 × 140 px. Bỏ trống để giữ logo template.</div>
    </div>
    @for($i = 0; $i < 4; $i++)
      <div class="col-md-6">
        @include('setting.home.partials.image-field', [
          'label' => 'Ảnh ' . ($i + 1),
          'current' => $images[$i] ?? '',
          'currentName' => "images[$i]",
          'fileName' => "image_files[$i]",
          'removeName' => "remove_images[$i]",
        ])
        <div class="form-text">Khuyên dùng ảnh du lịch/golf rõ chủ thể, tỷ lệ gần 4:3, tối thiểu 800 × 600 px.</div>
      </div>
    @endfor
  </div>
  <div class="text-end mt-3"><button class="btn btn-primary">Lưu Giới thiệu WAYLUNE</button></div>
</form>
