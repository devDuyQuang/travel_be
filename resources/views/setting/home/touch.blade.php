<x-slot name="touch_home">
      <div class="card mb-4 border">
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-12">
              <x-input-field
                name="touch_title"
                label="Tiêu Đề Thêm Liên Hệ"
                :value="old('value.touch_title', data_get($item->value, 'touch_title'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="touch_description"
                label="Mô Tả Thêm Liên Hệ"
                :value="old('value.touch_description', data_get($item->value, 'touch_description'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="touch_label_phone"
                label="Nhãn Điện Thoại"
                :value="old('value.touch_label_phone', data_get($item->value, 'touch_label_phone'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="touch_label_email"
                label="Nhãn Email"
                :value="old('value.touch_label_email', data_get($item->value, 'touch_label_email'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="touch_label_time"
                label="Nhãn Thời Gian Làm Việc"
                :value="old('value.touch_label_time', data_get($item->value, 'touch_label_time'))"
              />
            </div>
          </div>
        </div>
      </div>
    </x-slot>