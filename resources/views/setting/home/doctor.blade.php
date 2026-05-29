<x-slot name="doctor_home">
      <div class="card mb-4 border">
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6">
              <x-input-field
                name="doctor_title"
                label="Tiêu Đề Bác Sĩ"
                :value="old('value.doctor_title', data_get($item->value, 'doctor_title'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="doctor_description"
                label="Nội Dung Mô Tả"
                :value="old('value.doctor_description', data_get($item->value, 'doctor_description'))"
              />
            </div>
            <div class="col-md-12">
              <x-file-input
                label="Banner Bác Sĩ (PNG/JPG)"
                name="doctor_banner_file"
                :multiple="false"
                :current-url="$currentLogoUrl ?? null"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="doctor_number_statistic"
                label="Nội Dung Số Thống Kê"
                :value="old('value.doctor_number_statistic', data_get($item->value, 'doctor_number_statistic'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="doctor_text_statistic"
                label="Nội Dung Mô Tả Thống Kê"
                :value="old('value.doctor_text_statistic', data_get($item->value, 'doctor_text_statistic'))"
              />
            </div>
            <div class="col-md-12">
              <x-input-field
                name="doctor_title_skill"
                label="Tiêu Đề Kỹ Năng"
                :value="old('value.doctor_title_skill', data_get($item->value, 'doctor_title_skill'))"
              />
            </div>
          </div>
        </div>
      </div>
    </x-slot>