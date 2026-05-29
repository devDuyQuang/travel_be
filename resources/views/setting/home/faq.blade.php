<x-slot name="faq_home">
      <div class="card mb-4 border">
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6">
              <x-input-field
                name="faq_title"
                label="Tiêu Đề FAQ"
                :value="old('value.faq_title', data_get($item->value, 'faq_title'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="faq_description"
                label="Nội Dung Mô Tả"
                :value="old('value.faq_description', data_get($item->value, 'faq_description'))"
              />
            </div>
            <div class="col-md-12">
              <x-file-input
                label="Banner FAQ (PNG/JPG)"
                name="faq_banner_file"
                :multiple="false"
                :current-url="$currentLogoUrl ?? null"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="faq_text_button"
                label="Nội Dung Button"
                :value="old('value.faq_text_button', data_get($item->value, 'faq_text_button'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="faq_link_button"
                label="Link Button"
                :value="old('value.faq_link_button', data_get($item->value, 'faq_link_button'))"
              />
            </div>
            <div class="col-md-12">
              <x-input-field
                name="faq_title_contact"
                label="Nội Dung Tiêu Đề Liên Hệ"
                :value="old('value.faq_title_contact', data_get($item->value, 'faq_title_contact'))"
              />
            </div>
          </div>
        </div>
      </div>
    </x-slot>