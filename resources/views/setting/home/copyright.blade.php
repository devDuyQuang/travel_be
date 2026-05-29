<x-slot name="copyright_home">
      <div class="card mb-4 border">
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6">
              <x-input-field
                name="copyright_title"
                label="Tiêu Đề Copyright"
                :value="old('value.copyright_title', data_get($item->value, 'copyright_title'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="copyright_description"
                label="Mô Tả Copyright"
                :value="old('value.copyright_description', data_get($item->value, 'copyright_description'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="copyright_input_email"
                label="Nội Dung Input Email"
                :value="old('value.copyright_input_email', data_get($item->value, 'copyright_input_email'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="copyright_text_button"
                label="Nội Dung Text Button"
                :value="old('value.copyright_text_button', data_get($item->value, 'copyright_text_button'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="copyright_@"
                label="Nội Dung Copyright @"
                :value="old('value.copyright_@', data_get($item->value, 'copyright_@'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="copyright_site"
                label="Nội Dung Copyright Site"
                :value="old('value.copyright_site', data_get($item->value, 'copyright_site'))"
              />
            </div>
            <div class="col-md-6">
              <x-input-field
                name="copyright_content"
                label="Nội Dung Copyright Content"
                :value="old('value.copyright_content', data_get($item->value, 'copyright_content'))"
              />
            </div>
          </div>
        </div>
      </div>
    </x-slot>