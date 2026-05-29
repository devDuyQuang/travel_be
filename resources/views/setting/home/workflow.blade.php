<x-slot name="workflow_home">
      <div class="card mb-4 border">
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6">
              <x-input-field
                name="workflow_title"
                label="Tiêu Đề Quy Trình"
                :value="old('value.workflow_title', data_get($item->value, 'workflow_title'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="workflow_description"
                label="Nội Dung Mô Tả"
                :value="old('value.workflow_description', data_get($item->value, 'workflow_description'))"
              />
            </div>
            <div class="col-md-12">
              <x-file-input
                label="Banner Quy Trình (PNG/JPG)"
                name="workflow_banner_file"
                :multiple="false"
                :current-url="$currentLogoUrl ?? null"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="workflow_text_button"
                label="Nội Dung Button"
                :value="old('value.workflow_text_button', data_get($item->value, 'workflow_text_button'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="workflow_link_button"
                label="Link Button"
                :value="old('value.workflow_link_button', data_get($item->value, 'workflow_link_button'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="workflow_number_cot_1"
                label="Nội Dung Số Cột 1"
                :value="old('value.workflow_number_cot_1', data_get($item->value, 'workflow_number_cot_1'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="workflow_text_cot_1"
                label="Nội Dung Mô Tả Cột 1"
                :value="old('value.workflow_text_cot_1', data_get($item->value, 'workflow_text_cot_1'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="workflow_number_cot_2"
                label="Nội Dung Số Cột 2"
                :value="old('value.workflow_number_cot_2', data_get($item->value, 'workflow_number_cot_2'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="workflow_text_cot_2"
                label="Nội Dung Mô Tả Cột 2"
                :value="old('value.workflow_text_cot_2', data_get($item->value, 'workflow_text_cot_2'))"
              />
            </div>
          </div>
        </div>
      </div>
    </x-slot>