<x-slot name="slide_home">
    <div class="card mb-4 border">
         <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <x-input-field
                    name="hero_title_line_1"
                    label="Tiêu Đề Dòng 1"
                    :value="old('value.hero_title_line_1', data_get($item->value, 'hero_title_line_1'))"
                    />
                </div>
                <div class="col-md-6">
                    <x-input-field
                    name="hero_title_line_2"
                    label="Tiêu Đề Dòng 2"
                    :value="old('value.hero_title_line_2', data_get($item->value, 'hero_title_line_2'))"
                    />
                </div>
                <div class="col-md-6">
                    <x-input-field
                    name="hero_text_vertical"
                    label="Nội Dung Nằm Dọc"
                    :value="old('value.hero_text_vertical', data_get($item->value, 'hero_text_vertical'))"
                    />
                </div>
                <div class="col-md-6">
                    <x-input-field
                    name="hero_description"
                    label="Mô Tả"
                    :value="old('value.hero_description', data_get($item->value, 'hero_description'))"
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
                    name="hero_text_button_1"
                    label="Nội Dung Button 1"
                    :value="old('value.hero_text_button_1', data_get($item->value, 'hero_text_button_1'))"
                    />
                </div>
                <div class="col-md-6">
                    <x-input-field
                    name="hero_link_button_1"
                    label="Link Button 1"
                    :value="old('value.hero_link_button_1', data_get($item->value, 'hero_link_button_1'))"
                    />
                </div>
                <div class="col-md-6">
                    <x-input-field
                    name="hero_text_button_2"
                    label="Nội Dung Button 2"
                    :value="old('value.hero_text_button_2', data_get($item->value, 'hero_text_button_2'))"
                    />
                </div>
                <div class="col-md-6">
                    <x-input-field
                    name="hero_link_button_2"
                    label="Link Button 2"
                    :value="old('value.hero_link_button_2', data_get($item->value, 'hero_link_button_2'))"
                    />
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-4 border">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-12">
                    <x-input-field
                    name="hero_title_faq"
                    label="Tiêu Đề Hỏi Đáp"
                    :value="old('value.hero_title_faq', data_get($item->value, 'hero_title_faq'))"
                    />
                </div>
                <div class="col-md-12">
                    <x-file-input
                    label="Avatar Hỏi Đáp (PNG/JPG)"
                    name="hero_avatar_faq_file"
                    :multiple="false"
                    :current-url="$currentLogoUrl ?? null"
                    />
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-4 border">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-12">
                    <x-input-field
                    name="hero_title_percent"
                    label="Tiêu Đề %"
                    :value="old('value.hero_title_percent', data_get($item->value, 'hero_title_percent'))"
                    />
                </div>
                <div class="col-md-6">
                    <x-input-field
                    name="hero_number_percent"
                    label="Số %"
                    :value="old('value.hero_number_percent', data_get($item->value, 'hero_number_percent'))"
                    />
                </div>
                <div class="col-md-6">
                    <x-input-field
                    name="hero_link_percent"
                    label="Link %"
                    :value="old('value.hero_link_percent', data_get($item->value, 'hero_link_percent'))"
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
                    name="hero_title_patient"
                    label="Tiêu Đề Bệnh Nhân"
                    :value="old('value.hero_title_patient', data_get($item->value, 'hero_title_patient'))"
                    />
                </div>
                <div class="col-md-6">
                    <x-input-field
                    name="hero_number_patient"
                    label="Số Bệnh Nhân"
                    :value="old('value.hero_number_patient', data_get($item->value, 'hero_number_patient'))"
                    />
                </div>
                <div class="col-md-12">
                    <x-file-input
                    label="Banner Slide (PNG/JPG)"
                    name="hero_banner_file"
                    :multiple="false"
                    :current-url="$currentLogoUrl ?? null"
                    />
                </div>
            </div>
        </div>
    </div>
</x-slot>