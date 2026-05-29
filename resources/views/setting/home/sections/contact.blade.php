<form action="{{ panel_route('setting.updateContact') }}" method="POST" class="ajax-form">
  @csrf
  @method('PUT')
  <div class="row g-3">
    <div class="col-md-12">
      <div class="card shadow-none border bg-transparent">
        <div class="card-header border-bottom">
          <ul class="nav nav-tabs card-header-tabs" id="contactTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="contact-main-tab" data-bs-toggle="tab" data-bs-target="#contact-main" type="button" role="tab" aria-controls="contact-main" aria-selected="true">
                Thông tin & Liên hệ
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="contact-appointment-tab" data-bs-toggle="tab" data-bs-target="#contact-appointment" type="button" role="tab" aria-controls="contact-appointment" aria-selected="false">
                Nút Hẹn lịch
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="contact-map-tab" data-bs-toggle="tab" data-bs-target="#contact-map" type="button" role="tab" aria-controls="contact-map" aria-selected="false">
                Bản đồ Google Maps
              </button>
            </li>
          </ul>
        </div>
        <div class="card-body p-3">
          <div class="tab-content">
            {{-- Tab: Thông tin chung & liên hệ --}}
            <div class="tab-pane fade show active" id="contact-main" role="tabpanel" aria-labelledby="contact-main-tab">
              <div class="row g-3">
                <div class="col-md-12">
                  <label class="form-label fw-bold">Tiêu đề lớn (H1/H2)</label>
                  <input type="text" name="title" class="form-control" value="{{ $v['title'] ?? '' }}" placeholder="Get In Touch With Us">
                </div>
                <div class="col-md-12">
                  <label class="form-label fw-bold">Mô tả (P)</label>
                  <input type="text" name="description" class="form-control" value="{{ $v['description'] ?? '' }}" placeholder="Lorem Ipsum is simply dummy">
                </div>
                <hr class="my-3">
                <div class="col-md-12">
                  <label class="form-label fw-bold d-block text-primary"><i class="ti tabler-map-pin me-1"></i>Địa chỉ (Address)</label>
                  <div class="row g-2">
                    <div class="col-4"><input type="text" name="address[label]" class="form-control form-control-sm" value="{{ $v['address']['label'] ?? '' }}" placeholder="Label"></div>
                    <div class="col-8"><input type="text" name="address[value]" class="form-control form-control-sm" value="{{ $v['address']['value'] ?? '' }}" placeholder="Value"></div>
                  </div>
                </div>
                <div class="col-md-12">
                  <label class="form-label fw-bold d-block text-primary"><i class="ti tabler-phone mx-1"></i>Trực tuyến (Call Us)</label>
                  <div class="row g-2">
                    <div class="col-4"><input type="text" name="phone[label]" class="form-control form-control-sm" value="{{ $v['phone']['label'] ?? '' }}" placeholder="Label"></div>
                    <div class="col-8"><input type="text" name="phone[value]" class="form-control form-control-sm" value="{{ $v['phone']['value'] ?? '' }}" placeholder="Value"></div>
                  </div>
                </div>
                <div class="col-md-12">
                  <label class="form-label fw-bold d-block text-primary"><i class="ti tabler-mail me-1"></i>Thư điện tử (Email)</label>
                  <div class="row g-2">
                    <div class="col-4"><input type="text" name="email[label]" class="form-control form-control-sm" value="{{ $v['email']['label'] ?? '' }}" placeholder="Label"></div>
                    <div class="col-8"><input type="text" name="email[value]" class="form-control form-control-sm" value="{{ $v['email']['value'] ?? '' }}" placeholder="Value"></div>
                  </div>
                </div>
                <div class="col-md-12">
                  <label class="form-label fw-bold d-block text-primary"><i class="ti tabler-clock me-1"></i>Giờ làm việc (Opening Time)</label>
                  <div class="row g-2">
                    <div class="col-4"><input type="text" name="time[label]" class="form-control form-control-sm" value="{{ $v['time']['label'] ?? '' }}" placeholder="Label"></div>
                    <div class="col-8"><textarea name="time[value]" class="form-control form-control-sm" rows="3">{{ $v['time']['value'] ?? '' }}</textarea></div>
                  </div>
                </div>
              </div>
            </div>

            {{-- Tab: Nút hẹn lịch --}}
            <div class="tab-pane fade" id="contact-appointment" role="tabpanel" aria-labelledby="contact-appointment-tab">
              <div class="row g-3">
                <div class="col-12">
                  <label class="form-label fw-bold">Text hiển thị</label>
                  <input type="text" name="appointment_btn[text]" class="form-control" value="{{ $v['appointment_btn']['text'] ?? '' }}" placeholder="Appointment">
                </div>
                <div class="col-12">
                  <label class="form-label fw-bold">Link liên kết</label>
                  <input type="text" name="appointment_btn[link]" class="form-control" value="{{ $v['appointment_btn']['link'] ?? '' }}" placeholder="#">
                </div>
              </div>
            </div>

            {{-- Tab: Bản đồ Google Maps --}}
            <div class="tab-pane fade" id="contact-map" role="tabpanel" aria-labelledby="contact-map-tab">
              <label class="form-label fw-bold">Mã nhúng (Embed Code)</label>
              <textarea name="map_iframe" class="form-control font-monospace" rows="5" placeholder='<iframe src="..."></iframe>'>{{ $v['map_iframe'] ?? '' }}</textarea>
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
