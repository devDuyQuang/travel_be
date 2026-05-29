<x-slot name="blog_home">
      <div class="card mb-4 border">
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-12">
              <x-input-field
                name="blog_title"
                label="Tiêu Đề Bằng Cấp"
                :value="old('value.blog_title', data_get($item->value, 'blog_title'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="blog_text_button"
                label="Nội Dung Button"
                :value="old('value.blog_text_button', data_get($item->value, 'blog_text_button'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="blog_link_button"
                label="Link Button"
                :value="old('value.blog_link_button', data_get($item->value, 'blog_link_button'))"
              />
            </div>
          </div>
        </div>
      </div>
    </x-slot>