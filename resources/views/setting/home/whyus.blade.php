<x-slot name="whyus_home">
    <div class="card mb-4 border">
    <div class="card-body">
        <div class="row g-3">
        <div class="col-md-12">
            <x-file-input
            label="Banner Tại Sao Chọn (PNG/JPG)"
            name="whyus_banner_file"
            :multiple="false"
            :current-url="$currentLogoUrl ?? null"
            />
        </div>
        <div class="col-md-6">
            <x-input-field
            name="whyus_number"
            label="Nội Dung Số"
            :value="old('value.whyus_number', data_get($item->value, 'whyus_number'))"
            />
        </div>
        <div class="col-md-6">
            <x-input-field
            name="whyus_text"
            label="Nội Dung Mô Tả"
            :value="old('value.whyus_text', data_get($item->value, 'whyus_text'))"
            />
        </div>
        <div class="col-md-12">
            <x-input-field
            name="whyus_title"
            label="Tiêu Đề Tại Sao Chọn"
            :value="old('value.whyus_title', data_get($item->value, 'whyus_title'))"
            />
        </div>
        </div>
    </div>
    </div>
</x-slot>