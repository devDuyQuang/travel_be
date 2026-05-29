<x-slot name="patient_home">
      <div class="card mb-4 border">
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-12">
              <x-file-input
                label="Banner Bệnh Nhân (PNG/JPG)"
                name="patient_banner_file"
                :multiple="false"
                :current-url="$currentLogoUrl ?? null"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="patient_number"
                label="Nội Dung Số"
                :value="old('value.patient_number', data_get($item->value, 'patient_number'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="patient_text"
                label="Nội Dung Mô Tả"
                :value="old('value.patient_text', data_get($item->value, 'patient_text'))"
              />
            </div>
            <div class="col-md-12">
              <x-input-field
                name="patient_title"
                label="Tiêu Đề Bệnh Nhân"
                :value="old('value.patient_title', data_get($item->value, 'patient_title'))"
              />
            </div>
          </div>
        </div>
      </div>
    </x-slot>