<form action="{{ panel_route('setting.updateHero', $item->id ?? 1) }}" method="POST" enctype="multipart/form-data" class="ajax-form">
  @csrf
  @method('PUT')
  <div class="row g-3">
    <div class="col-md-12">
      <div class="card shadow-none border bg-transparent">
        <div class="card-header border-bottom">
          <ul class="nav nav-tabs card-header-tabs" id="heroTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="hero-main-tab" data-bs-toggle="tab" data-bs-target="#hero-main" type="button" role="tab" aria-controls="hero-main" aria-selected="true">
                Nội dung chính & Banner
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="hero-question-tab" data-bs-toggle="tab" data-bs-target="#hero-question" type="button" role="tab" aria-controls="hero-question" aria-selected="false">
                Thông tin câu hỏi
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="hero-percent-tab" data-bs-toggle="tab" data-bs-target="#hero-percent" type="button" role="tab" aria-controls="hero-percent" aria-selected="false">
                Tỷ lệ hài lòng
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="hero-patient-tab" data-bs-toggle="tab" data-bs-target="#hero-patient" type="button" role="tab" aria-controls="hero-patient" aria-selected="false">
                Thông tin bệnh nhân
              </button>
            </li>
          </ul>
        </div>
        <div class="card-body p-3">
          <div class="tab-content">
            {{-- Tab: Nội dung chính & banner --}}
            <div class="tab-pane fade show active" id="hero-main" role="tabpanel" aria-labelledby="hero-main-tab">
              <div class="row g-3">
                <div class="col-md-6"><x-input-field name="title" label="Tiêu đề chính" :value="old('title', data_get($item->value, 'title'))" /></div>
                <div class="col-md-6"><x-input-field name="description" label="Mô tả" :value="old('description', data_get($item->value, 'description'))" /></div>
                <div class="col-md-6"><x-input-field name="button_one_text" label="Text Button 1" :value="old('button_one_text', data_get($item->value, 'button_one_text'))" /></div>
                <div class="col-md-6"><x-input-field name="button_one_link" label="Link Button 1" :value="old('button_one_link', data_get($item->value, 'button_one_link'))" /></div>
                <div class="col-md-6"><x-input-field name="button_two_text" label="Text Button 2" :value="old('button_two_text', data_get($item->value, 'button_two_text'))" /></div>
                <div class="col-md-6"><x-input-field name="button_two_link" label="Link Button 2" :value="old('button_two_link', data_get($item->value, 'button_two_link'))" /></div>
                <div class="col-md-12"><x-file-input label="Banner Hero" name="banner_hero_file" :multiple="false" :current-url="$currentBannerHeroUrl ?? null" /></div>
              </div>
            </div>

            {{-- Tab: Thông tin câu hỏi --}}
            <div class="tab-pane fade" id="hero-question" role="tabpanel" aria-labelledby="hero-question-tab">
              <div class="row g-3">
                <div class="col-md-12"><x-input-field name="question_title" label="Tiêu đề câu hỏi" :value="old('question_title', data_get($item->value, 'question_title'))" /></div>
                <div class="col-md-12"><x-input-field name="question_email" label="Email nhận câu hỏi" :value="old('question_email', data_get($item->value, 'question_email'))" /></div>
              </div>
            </div>

            {{-- Tab: Tỷ lệ hài lòng --}}
            <div class="tab-pane fade" id="hero-percent" role="tabpanel" aria-labelledby="hero-percent-tab">
              <div class="row g-3">
                <div class="col-md-12"><x-input-field name="percent" label="Phần trăm (%)" :value="old('percent', data_get($item->value, 'percent'))" /></div>
                <div class="col-md-12"><x-input-field name="percent_text" label="Text phần trăm" :value="old('percent_text', data_get($item->value, 'percent_text'))" /></div>
                <div class="col-md-12"><x-input-field name="percent_link" label="Link phần trăm" :value="old('percent_link', data_get($item->value, 'percent_link'))" /></div>
              </div>
            </div>

            {{-- Tab: Thông tin bệnh nhân --}}
            <div class="tab-pane fade" id="hero-patient" role="tabpanel" aria-labelledby="hero-patient-tab">
              <div class="row g-3">
                <div class="col-md-6"><x-input-field name="patient_title" label="Tiêu đề bệnh nhân" :value="old('patient_title', data_get($item->value, 'patient_title'))" /></div>
                <div class="col-md-6"><x-input-field name="patient_des" label="Mô tả bệnh nhân" :value="old('patient_des', data_get($item->value, 'patient_des'))" /></div>
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
