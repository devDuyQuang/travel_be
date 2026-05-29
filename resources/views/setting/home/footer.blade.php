<x-slot name="footer_home">
      <div class="card mb-4 border">
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-12">
              <x-file-input
                label="Logo Footer (PNG/JPG)"
                name="footer_logo_file"
                :multiple="false"
                :current-url="$currentLogoUrl ?? null"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="footer_title"
                label="Tiêu Đề Footer"
                :value="old('value.footer_title', data_get($item->value, 'footer_title'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="footer_description"
                label="Mô Tả Footer"
                :value="old('value.footer_description', data_get($item->value, 'footer_description'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="footer_label_cot_1"
                label="Nhãn Cột 1"
                :value="old('value.footer_label_cot_1', data_get($item->value, 'footer_label_cot_1'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="footer_label_cot_2"
                label="Nhãn Cột 2"
                :value="old('value.footer_label_cot_2', data_get($item->value, 'footer_label_cot_2'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="footer_label_cot_3"
                label="Nhãn Cột 3"
                :value="old('value.footer_label_cot_3', data_get($item->value, 'footer_label_cot_3'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="footer_label_cot_4"
                label="Nhãn Cột 4"
                :value="old('value.footer_label_cot_4', data_get($item->value, 'footer_label_cot_4'))"
              />
            </div>
          </div>
        </div>
      </div>
    </x-slot>