<x-slot name="awards_home">
      <div class="card mb-4 border">
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6">
              <x-input-field
                name="awards_title"
                label="Tiêu Đề Bằng Cấp"
                :value="old('value.awards_title', data_get($item->value, 'awards_title'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="awards_description"
                label="Nội Dung Mô Tả"
                :value="old('value.awards_description', data_get($item->value, 'awards_description'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="awards_text_button"
                label="Nội Dung Button"
                :value="old('value.awards_text_button', data_get($item->value, 'awards_text_button'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="awards_link_button"
                label="Link Button"
                :value="old('value.awards_link_button', data_get($item->value, 'awards_link_button'))"
              />
            </div>
          </div>
        </div>
      </div>
    </x-slot>