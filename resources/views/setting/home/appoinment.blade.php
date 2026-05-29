<x-slot name="appoinment_home">
      <div class="card mb-4 border">
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-12">
              <x-file-input
                label="Banner Đặt Hẹn (PNG/JPG)"
                name="appoinment_banner_file"
                :multiple="false"
                :current-url="$currentLogoUrl ?? null"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="appoinment_content_vertical"
                label="Nội Dung Nằm Dọc"
                :value="old('value.appoinment_content_vertical', data_get($item->value, 'appoinment_content_vertical'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="appoinment_title_content_one"
                label="Tiêu Đề Nội Dung 1"
                :value="old('value.appoinment_title_content_one', data_get($item->value, 'appoinment_title_content_one'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="appoinment_title_content_two"
                label="Tiêu Đề Nội Dung 2"
                :value="old('value.appoinment_title_content_two', data_get($item->value, 'appoinment_title_content_two'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="appoinment_title_content_three"
                label="Tiêu Đề Nội Dung 3"
                :value="old('value.appoinment_title_content_three', data_get($item->value, 'appoinment_title_content_three'))"
              />
            </div>
          </div>
        </div>
      </div>
      <div class="card mb-4 border">
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6">
              <x-input-field
                name="appoinment_form_fullname"
                label="Nội Dung Họ & Tên"
                :value="old('value.appoinment_form_fullname', data_get($item->value, 'appoinment_form_fullname'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="appoinment_form_email"
                label="Nội Dung Email"
                :value="old('value.appoinment_form_email', data_get($item->value, 'appoinment_form_email'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="appoinment_form_phone"
                label="Nội Dung Số Điện Thoại"
                :value="old('value.appoinment_form_phone', data_get($item->value, 'appoinment_form_phone'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="appoinment_form_message"
                label="Nội Dung Tin Nhắn"
                :value="old('value.appoinment_form_message', data_get($item->value, 'appoinment_form_message'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="appoinment_text_button"
                label="Nội Dung Button"
                :value="old('value.appoinment_text_button', data_get($item->value, 'appoinment_text_button'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="appoinment_link_button"
                label="Link Button"
                :value="old('value.appoinment_link_button', data_get($item->value, 'appoinment_link_button'))"
              />
            </div>
          </div>
        </div>
      </div>
    </x-slot>