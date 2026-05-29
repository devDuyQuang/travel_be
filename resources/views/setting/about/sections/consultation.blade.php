<form action="{{ panel_route('setting.updateAboutConsultation') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
  @csrf
  @method('PUT')
  @php $vc = $v ?? []; @endphp

  <div class="row g-3 p-2">
    <div class="col-12">
      <div class="card shadow-none border bg-transparent">
        <div class="card-header border-bottom">
          <ul class="nav nav-tabs card-header-tabs" role="tablist">
            <li class="nav-item">
              <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#consult-main" type="button">
                Nội Dung &amp; Hình Ảnh
              </button>
            </li>
          </ul>
        </div>
        <div class="card-body p-3">
          <div class="tab-content">
            <div class="tab-pane fade show active" id="consult-main" role="tabpanel">
              <div class="row g-3">
                <div class="col-md-12">
                  <x-input-field label="Tiêu đề" name="title" :value="$vc['title'] ?? ''" placeholder="Schedule Your Consultation Today!" />
                </div>
                <div class="col-md-6">
                  <x-input-field label="Text nút (Button Text)" name="btn_text" :value="$vc['btn_text'] ?? ''" placeholder="Appointment" />
                </div>
                <div class="col-md-6">
                  <x-input-field label="Link nút (Button Link)" name="btn_link" :value="$vc['btn_link'] ?? ''" placeholder="#" />
                </div>
                <div class="col-md-12">
                <x-file-input label="Hình ảnh nền (1400×1020)" name="consultation_image" :multiple="false" :current-url="$currentImageUrl ?? null" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 mt-2 text-end">
      <button type="submit" class="btn btn-primary me-2">Lưu thay đổi</button>
      <button type="reset" class="btn btn-label-secondary">Hủy</button>
    </div>
  </div>
</form>
