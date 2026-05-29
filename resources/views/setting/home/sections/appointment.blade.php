<form action="{{ panel_route('setting.updateAppointment') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
  @csrf
  @method('PUT')
  <input type="hidden" name="type" value="{{ $settingType ?? 'clinic' }}">
  <input type="hidden" name="tab" value="appointment">
  <div class="row g-3">
    <div class="col-md-12">
      <div class="card shadow-none border bg-transparent">
        <div class="card-header border-bottom">
          <ul class="nav nav-tabs card-header-tabs" id="appointmentTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="appointment-image-tab" data-bs-toggle="tab" data-bs-target="#appointment-image" type="button" role="tab" aria-controls="appointment-image" aria-selected="true">
                Hình ảnh Section
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="appointment-content-tab" data-bs-toggle="tab" data-bs-target="#appointment-content" type="button" role="tab" aria-controls="appointment-content" aria-selected="false">
                Nội dung văn bản
              </button>
            </li>
          </ul>
        </div>
        <div class="card-body p-3">
          <div class="tab-content">
            {{-- Tab: Hình ảnh --}}
            <div class="tab-pane fade show active" id="appointment-image" role="tabpanel" aria-labelledby="appointment-image-tab">
              <div class="row align-items-center">
                <div class="col-md-8">
                  <label class="form-label">Chọn ảnh hiển thị bên trái (600x600)</label>
                  <input type="file" name="image_file" id="image-input" class="form-control" accept="image/*">
                </div>
                <div class="col-md-4 text-center">
                  <div id="image-preview-container" class="border rounded p-2 bg-dark-subtle d-flex align-items-center justify-content-center" style="height: 200px; overflow: hidden;">
                    @if(!empty($currentImageUrl))
                      <img src="{{ $currentImageUrl }}" id="image-preview" class="h-100 object-fit-contain rounded" alt="Preview">
                    @else
                      <div id="image-placeholder" class="text-muted"><i class="ti tabler-photo ti-lg"></i><br>Chưa có ảnh</div>
                      <img src="" id="image-preview" class="h-100 object-fit-contain rounded d-none" alt="Preview">
                    @endif
                  </div>
                </div>
              </div>
            </div>

            {{-- Tab: Nội dung văn bản --}}
            <div class="tab-pane fade" id="appointment-content" role="tabpanel" aria-labelledby="appointment-content-tab">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label fw-bold">Tiêu đề chính</label>
                  <input type="text" name="title" class="form-control" value="{{ $v['title'] ?? '' }}" placeholder="Make An Appointment">
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold">Tiêu đề phụ</label>
                  <input type="text" name="subtitle" class="form-control" value="{{ $v['subtitle'] ?? '' }}" placeholder="Apply For Treatments">
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold">Dòng chữ dọc (Vertical Text)</label>
                  <input type="text" name="appointment_now_text" class="form-control" value="{{ $v['appointment_now_text'] ?? '' }}" placeholder="APPOINTMENT NOW">
                </div>
                <div class="col-md-3">
                  <label class="form-label fw-bold">Text nút</label>
                  <input type="text" name="button_text" class="form-control" value="{{ $v['button_text'] ?? '' }}" placeholder="Appointment">
                </div>
                <div class="col-md-3">
                  <label class="form-label fw-bold">Link nút</label>
                  <input type="text" name="button_link" class="form-control" value="{{ $v['button_link'] ?? '' }}" placeholder="/appointment">
                </div>
              </div>
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
@push('scripts')
<script>
$(function() {
  $('#image-input').on('change', function() {
    var file = this.files[0];
    if (file) {
      var r = new FileReader();
      r.onload = function(e) { $('#image-preview').attr('src', e.target.result).removeClass('d-none'); $('#image-placeholder').addClass('d-none'); };
      r.readAsDataURL(file);
    }
  });
});
</script>
@endpush
