<!-- <form action="{{ panel_route('setting.updateContactInfo') }}" method="POST" class="ajax-form">
  @csrf
  @method('PUT')
  <div class="row g-3">
    <div class="col-md-12">
      <div class="card shadow-none border bg-transparent">
        <div class="card-header border-bottom">
          <ul class="nav nav-tabs card-header-tabs" id="contactInfoTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="cinfo-main-tab" data-bs-toggle="tab" data-bs-target="#cinfo-main" type="button" role="tab" aria-controls="cinfo-main" aria-selected="true">
                Thông tin & Liên hệ
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="cinfo-appt-tab" data-bs-toggle="tab" data-bs-target="#cinfo-appt" type="button" role="tab" aria-controls="cinfo-appt" aria-selected="false">
                Nút Hẹn Lịch
              </button>
            </li>
          </ul>
        </div>
        <div class="card-body p-3">
          <div class="tab-content">

            {{-- Tab: Thông tin chung & liên hệ --}}
            <div class="tab-pane fade show active" id="cinfo-main" role="tabpanel" aria-labelledby="cinfo-main-tab">
              <div class="row g-3">
                <div class="col-md-12">
                  <label class="form-label fw-bold">Tiêu đề lớn (H1/H2)</label>
                  <input type="text" name="title" class="form-control" value="{{ $v['title'] ?? '' }}" placeholder="Connect With Us For Your Healthcare Needs">
                </div>
                <div class="col-md-12">
                  <label class="form-label fw-bold">Mô tả (P)</label>
                  <textarea name="description" class="form-control" rows="3" placeholder="Liên hệ Golfnity để được tư vấn dịch vụ...">{{ $v['description'] ?? '' }}</textarea>
                </div>

                <hr class="my-2">

                {{-- Address --}}
                <div class="col-md-12">
                  <label class="form-label fw-bold d-block text-primary"><i class="ti tabler-map-pin me-1"></i>Địa chỉ (Address)</label>
                  <div class="row g-2">
                    <div class="col-4">
                      <input type="text" name="address[label]" class="form-control form-control-sm" value="{{ $v['address']['label'] ?? '' }}" placeholder="Label: Address">
                    </div>
                    <div class="col-8">
                      <input type="text" name="address[value]" class="form-control form-control-sm" value="{{ $v['address']['value'] ?? '' }}" placeholder="234 Oak Drive, Villagetown, USA">
                    </div>
                  </div>
                </div>

                {{-- Phone --}}
                <div class="col-md-12">
                  <label class="form-label fw-bold d-block text-primary"><i class="ti tabler-phone me-1"></i>Điện thoại (Call Us)</label>
                  <div class="row g-2">
                    <div class="col-4">
                      <input type="text" name="phone[label]" class="form-control form-control-sm" value="{{ $v['phone']['label'] ?? '' }}" placeholder="Label: Call Us">
                    </div>
                    <div class="col-8">
                      <input type="text" name="phone[value]" class="form-control form-control-sm" value="{{ $v['phone']['value'] ?? '' }}" placeholder="+1 123 456 7890">
                    </div>
                  </div>
                </div>

                {{-- Email --}}
                <div class="col-md-12">
                  <label class="form-label fw-bold d-block text-primary"><i class="ti tabler-mail me-1"></i>Email (Send us a Mail)</label>
                  <div class="row g-2">
                    <div class="col-4">
                      <input type="text" name="email[label]" class="form-control form-control-sm" value="{{ $v['email']['label'] ?? '' }}" placeholder="Label: Send us a Mail">
                    </div>
                    <div class="col-8">
                      <input type="text" name="email[value]" class="form-control form-control-sm" value="{{ $v['email']['value'] ?? '' }}" placeholder="email@domain.com">
                    </div>
                  </div>
                </div>

                {{-- Opening Time --}}
                <div class="col-md-12">
                  <label class="form-label fw-bold d-block text-primary"><i class="ti tabler-clock me-1"></i>Giờ làm việc (Opening Time)</label>
                  <div class="row g-2">
                    <div class="col-4">
                      <input type="text" name="time[label]" class="form-control form-control-sm" value="{{ $v['time']['label'] ?? '' }}" placeholder="Label: Opening Time">
                    </div>
                    <div class="col-8">
                      <textarea name="time[value]" class="form-control form-control-sm" rows="3" placeholder="Mon-Thu: 8:00am-5:00pm&#10;Fri: 8:00am-1:00pm">{{ $v['time']['value'] ?? '' }}</textarea>
                    </div>
                  </div>
                </div>

                {{-- Stats bar --}}
                <hr class="my-2">
                <div class="col-md-12">
                  <label class="form-label fw-bold d-block text-primary"><i class="ti tabler-users me-1"></i>Thanh thông tin hỗ trợ</label>
                  <div class="row g-2">
                    <div class="col-12">
                      <input type="text" name="stats_text" class="form-control form-control-sm" value="{{ $v['stats_text'] ?? '' }}" placeholder="Đội ngũ Golfnity luôn sẵn sàng hỗ trợ">
                    </div>
                  </div>
                </div>

                {{-- Rating --}}
                <div class="col-md-12">
                  <label class="form-label fw-bold d-block text-primary"><i class="ti tabler-star me-1"></i>Đánh giá (Rating)</label>
                  <div class="row g-2">
                    <div class="col-4">
                      <input type="text" name="rating[score]" class="form-control form-control-sm" value="{{ $v['rating']['score'] ?? '' }}" placeholder="4.8">
                    </div>
                    <div class="col-8">
                      <input type="text" name="rating[text]" class="form-control form-control-sm" value="{{ $v['rating']['text'] ?? '' }}" placeholder="12k+ ratings on google">
                    </div>
                  </div>
                </div>
              </div>
            </div>

            {{-- Tab: Nút hẹn lịch --}}
            <div class="tab-pane fade" id="cinfo-appt" role="tabpanel" aria-labelledby="cinfo-appt-tab">
              <div class="row g-3">
                <div class="col-12">
                  <label class="form-label fw-bold">Text hiển thị</label>
                  <input type="text" name="contact_btn[text]" class="form-control" value="{{ $v['contact_btn']['text'] ?? '' }}" placeholder="Liên hệ tư vấn">
                </div>
                <div class="col-12">
                  <label class="form-label fw-bold">Link liên kết</label>
                  <input type="text" name="contact_btn[link]" class="form-control" value="{{ $v['contact_btn']['link'] ?? '' }}" placeholder="#">
                </div>
                <div class="col-12">
                  <label class="form-label fw-bold">Form tiêu đề phụ (Get In Touch)</label>
                  <input type="text" name="form_title" class="form-control" value="{{ $v['form_title'] ?? '' }}" placeholder="Get In Touch">
                </div>
                <div class="col-12">
                  <label class="form-label fw-bold">Form mô tả phụ</label>
                  <input type="text" name="form_subtitle" class="form-control" value="{{ $v['form_subtitle'] ?? '' }}" placeholder="You can react us anytime">
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
</form> -->
<form action="{{ panel_route('setting.updateContactInfo') }}" method="POST" class="ajax-form">
  @csrf
  @method('PUT')

  <div class="row g-3">
    <div class="col-md-12">
      <div class="card shadow-none border bg-transparent">
        <div class="card-header border-bottom">
          <ul class="nav nav-tabs card-header-tabs" id="contactInfoTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="cinfo-main-tab" data-bs-toggle="tab" data-bs-target="#cinfo-main" type="button" role="tab">
                Thông tin & Liên hệ
              </button>
            </li>

            <li class="nav-item" role="presentation">
              <button class="nav-link" id="cinfo-appt-tab" data-bs-toggle="tab" data-bs-target="#cinfo-appt" type="button" role="tab">
                Nút Hẹn Lịch
              </button>
            </li>

            <li class="nav-item" role="presentation">
              <button class="nav-link" id="cinfo-form-tab" data-bs-toggle="tab" data-bs-target="#cinfo-form" type="button" role="tab">
                Form Liên Hệ
              </button>
            </li>
          </ul>
        </div>

        <div class="card-body p-3">
          <div class="tab-content">

            <div class="tab-pane fade show active" id="cinfo-main" role="tabpanel">
              <div class="row g-3">
                <div class="col-12">
                  <div class="alert alert-info mb-0">
                    Số điện thoại, email, website, địa chỉ và bản đồ được dùng chung toàn website.
                    Chỉnh tại <strong>Cấu Hình Site → Cấu Hình Chung → Thông tin công ty</strong>.
                  </div>
                </div>
                <div class="col-md-12">
                  <label class="form-label fw-bold">Tiêu đề khối thông tin</label>
                  <input type="text" name="info_title" class="form-control" value="{{ $v['info_title'] ?? '' }}" placeholder="Information:">
                </div>
                <div class="col-md-12">
                  <label class="form-label fw-bold">Mô tả khối thông tin</label>
                  <textarea name="info_description" class="form-control" rows="3" placeholder="Information description...">{{ $v['info_description'] ?? '' }}</textarea>
                </div>
              </div>
            </div>

            <div class="tab-pane fade" id="cinfo-appt" role="tabpanel">
              <div class="row g-3">
                <div class="col-12">
                  <label class="form-label fw-bold">Text hiển thị</label>
                  <input type="text" name="contact_btn[text]" class="form-control" value="{{ $v['contact_btn']['text'] ?? '' }}" placeholder="Liên hệ tư vấn">
                </div>

                <div class="col-12">
                  <label class="form-label fw-bold">Link liên kết</label>
                  <input type="text" name="contact_btn[link]" class="form-control" value="{{ $v['contact_btn']['link'] ?? '' }}" placeholder="/contact">
                </div>
              </div>
            </div>

            <div class="tab-pane fade" id="cinfo-form" role="tabpanel">
              <div class="row g-3">
                <div class="col-12">
                  <label class="form-label fw-bold">Tiêu đề form liên hệ</label>
                  <input type="text" name="form_title" class="form-control" value="{{ $v['form_title'] ?? '' }}" placeholder="Let's Connect And Get To Know Each Other">
                </div>

                <div class="col-12">
                  <label class="form-label fw-bold">Mô tả form liên hệ</label>
                  <input type="text" name="form_subtitle" class="form-control" value="{{ $v['form_subtitle'] ?? '' }}" placeholder="Form description...">
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
