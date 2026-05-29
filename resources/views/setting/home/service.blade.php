<x-slot name="service_home">
    <div class="card mb-4 border">
    <div class="card-body">
        <div class="row g-3">
        <div class="col-md-12">
            <x-input-field
            name="service_title"
            label="Tiêu Đề"
            :value="old('value.service_title', data_get($item->value, 'service_title'))"
            />
        </div>
        <div class="col-md-6">
            <x-input-field
            name="service_text_button"
            label="Nội Dung Button"
            :value="old('value.service_text_button', data_get($item->value, 'service_text_button'))"
            />
        </div>
        <div class="col-md-6">
            <x-input-field
            name="service_link_button"
            label="Link Button"
            :value="old('value.service_link_button', data_get($item->value, 'service_link_button'))"
            />
        </div>
        </div>
    </div>
    </div>
</x-slot>