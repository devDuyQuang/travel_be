<x-slot name="facilities_home">
    <div class="card mb-4 border">
        <div class="card-body">
            <div class="row g-3">
            <div class="col-md-12">
                <x-input-field
                name="facilities_title_vertical"
                label="Nội Dung Nằm Dọc"
                :value="old('value.facilities_title_vertical', data_get($item->value, 'facilities_title_vertical'))"
                />
            </div>
            <div class="col-md-12">
                <x-file-input
                label="Avatar Nằm Dọc (PNG/JPG)"
                name="facilities_avatar_vertical_file"
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
                name="facilities_title_schedule"
                label="Tiêu Đề Thời Gian Làm Việc"
                :value="old('value.facilities_title_schedule', data_get($item->value, 'facilities_title_schedule'))"
                />
            </div>
            <div class="col-md-12">
                <x-file-input
                label="Banner Tiện Ích (PNG/JPG)"
                name="facilities_banner_file"
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
                name="facilities_title"
                label="Tiêu Đề Chính"
                :value="old('value.facilities_title', data_get($item->value, 'facilities_title'))"
                />
            </div>
            <div class="col-md-12">
                <x-textarea-field
                name="facilities_description"
                label="Mô Tả"
                :value="old('value.facilities_description', data_get($item->value, 'facilities_description'))"
                rows="3"
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
                name="facilities_text_button"
                label="Nội Dung Button"
                :value="old('value.facilities_text_button', data_get($item->value, 'facilities_text_button'))"
                />
            </div>
            <div class="col-md-6">
                <x-input-field
                name="facilities_link_button"
                label="Link Button"
                :value="old('value.facilities_link_button', data_get($item->value, 'facilities_link_button'))"
                />
            </div>
            <div class="col-md-12">
                <x-input-field
                name="facilities_title_contact"
                label="Tiêu Đề Liên Hệ"
                :value="old('value.facilities_title_contact', data_get($item->value, 'facilities_title_contact'))"
                />
            </div>
            </div>
        </div>
    </div>
</x-slot>