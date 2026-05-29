<x-slot name="contact_home">
      <div class="card mb-4 border">
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6">
              <x-input-field
                name="contact_title"
                label="Tiêu Đề Liên Hệ"
                :value="old('value.contact_title', data_get($item->value, 'contact_title'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="contact_description"
                label="Mô Tả Liên Hệ"
                :value="old('value.contact_description', data_get($item->value, 'contact_description'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="contact_label_address"
                label="Nhãn Địa Chỉ"
                :value="old('value.contact_label_address', data_get($item->value, 'contact_label_address'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="contact_label_phone"
                label="Nhãn Điện Thoại"
                :value="old('value.contact_label_phone', data_get($item->value, 'contact_label_phone'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="contact_label_email"
                label="Nhãn Email"
                :value="old('value.contact_label_email', data_get($item->value, 'contact_label_email'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="contact_label_time"
                label="Nhãn Thời Gian Làm Việc"
                :value="old('value.contact_label_time', data_get($item->value, 'contact_label_time'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="contact_text_button"
                label="Nội Dung Button"
                :value="old('value.contact_text_button', data_get($item->value, 'contact_text_button'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="contact_link_button"
                label="Link Button"
                :value="old('value.contact_link_button', data_get($item->value, 'contact_link_button'))"
              />
            </div>
          </div>
        </div>
      </div>
    </x-slot>