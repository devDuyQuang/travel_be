<form action="{{ panel_route('setting.updateAwards') }}" method="POST" class="ajax-form">
  @csrf
  @method('PUT')

  <div class="row g-3">
    <div class="col-md-12">
      <div class="card shadow-none border bg-transparent">
        <div class="card-header border-bottom">
          <span class="fw-bold text-primary">Thông tin section Bằng cấp</span>
        </div>

        <div class="card-body pt-4">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label fw-bold">Tiêu đề section</label>
              <input
                type="text"
                name="title"
                class="form-control"
                value="{{ $v['title'] ?? '' }}"
                placeholder="Bằng cấp"
              >
            </div>

            <div class="col-12">
              <label class="form-label fw-bold">Mô tả</label>
              <textarea
                name="description"
                class="form-control"
                rows="4"
                placeholder="Khẳng định uy tín chuyên môn bằng những chứng nhận chuyên môn và sự tin tưởng từ cộng đồng."
              >{{ $v['description'] ?? '' }}</textarea>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 mt-3 text-end">
      <button type="submit" class="btn btn-primary me-2">Lưu thay đổi</button>
      <button type="reset" class="btn btn-label-secondary">Hủy</button>
    </div>
  </div>
</form>