<form action="{{ panel_route('setting.home.update', ['section' => 'destinations']) }}" method="POST" class="ajax-form">
  @csrf @method('PUT')
  <input type="hidden" name="enabled" value="0">
  <div class="alert alert-info">Hệ thống chưa có module Điểm đến riêng. CMS chỉ quản lý tiêu đề; danh sách card vẫn giữ dữ liệu template.</div>
  <div class="row g-3">
    <div class="col-12"><label class="form-check form-switch"><input class="form-check-input" type="checkbox" name="enabled" value="1" @checked($enabled ?? true)><span class="form-check-label">Hiển thị section</span></label></div>
    <div class="col-md-4"><label class="form-label">Dòng giới thiệu</label><input class="form-control" name="subtitle" value="{{ $subtitle ?? '' }}"></div>
    <div class="col-md-8"><label class="form-label">Tiêu đề</label><input class="form-control" name="title" value="{{ $title ?? '' }}"></div>
    <div class="col-12"><label class="form-label">Mô tả</label><textarea class="form-control" rows="2" name="description">{{ $description ?? '' }}</textarea></div>
    <div class="col-md-4"><label class="form-label">Số card</label><input class="form-control" type="number" min="1" max="12" name="limit" value="{{ $limit ?? 4 }}"></div>
  </div>
  <div class="text-end mt-3"><button class="btn btn-primary">Lưu Điểm đến nổi bật</button></div>
</form>
