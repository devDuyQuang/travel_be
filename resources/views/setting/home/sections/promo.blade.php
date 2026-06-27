<form action="{{ panel_route('setting.home.update', ['section' => 'promo']) }}" method="POST" enctype="multipart/form-data" class="ajax-form">
  @csrf @method('PUT')
  <input type="hidden" name="enabled" value="0">
  <div class="row g-3">
    <div class="col-12"><label class="form-check form-switch"><input class="form-check-input" type="checkbox" name="enabled" value="1" @checked($enabled ?? true)><span class="form-check-label">Hiển thị section</span></label></div>
    <div class="col-md-6">@include('setting.home.partials.image-field', ['label' => 'Ảnh cover', 'current' => $cover_image ?? '', 'currentName' => 'cover_image', 'fileName' => 'cover_image_file', 'removeName' => 'remove_cover_image'])</div>
    <div class="col-md-6"><label class="form-label">Video URL</label><input class="form-control" name="video_url" value="{{ $video_url ?? '' }}"></div>
    <div class="col-md-4"><label class="form-label">Dòng giới thiệu</label><input class="form-control" name="subtitle" value="{{ $subtitle ?? '' }}"></div>
    <div class="col-md-8"><label class="form-label">Tiêu đề ưu đãi</label><input class="form-control" name="title" value="{{ $title ?? '' }}"></div>
    <div class="col-12"><label class="form-label">Mô tả</label><textarea class="form-control" rows="2" name="description">{{ $description ?? '' }}</textarea></div>
    <div class="col-md-6"><label class="form-label">Nhãn nút</label><input class="form-control" name="button_text" value="{{ $button_text ?? '' }}"></div>
    <div class="col-md-6"><label class="form-label">Liên kết nút</label><input class="form-control" name="button_link" value="{{ $button_link ?? '' }}"></div>
  </div>
  <div class="text-end mt-3"><button class="btn btn-primary">Lưu Video & ưu đãi</button></div>
</form>
