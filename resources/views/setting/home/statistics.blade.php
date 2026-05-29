<x-slot name="statistics_home">
    <div class="card mb-4 border">
    <div class="card-body">
        <div class="row g-3">
        <div class="col-md-12">
            <x-input-field
            name="statistics_content_one"
            label="Nội Dung Cột 1"
            :value="old('value.statistics_content_one', data_get($item->value, 'statistics_content_one'))"
            />
        </div>
        <div class="col-md-12">
            <x-file-input
            label="Banner Thống Kê (PNG/JPG)"
            name="statistics_banner_file"
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
        <div class="col-md-6">
            <x-input-field
            name="statistics_number_two"
            label="Số Cột 2"
            :value="old('value.statistics_number_two', data_get($item->value, 'statistics_number_two'))"
            />
        </div>
        <div class="col-md-6">
            <x-input-field
            name="statistics_content_two"
            label="Nội Dung Cột 2"
            :value="old('value.statistics_content_two', data_get($item->value, 'statistics_content_two'))"
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
            name="statistics_number_three"
            label="Số Cột 3"
            :value="old('value.statistics_number_three', data_get($item->value, 'statistics_number_three'))"
            />
        </div>
        <div class="col-md-6">
            <x-input-field
            name="statistics_content_three"
            label="Nội Dung Cột 3"
            :value="old('value.statistics_content_three', data_get($item->value, 'statistics_content_three'))"
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
            name="statistics_number_four"
            label="Số Cột 4"
            :value="old('value.statistics_number_four', data_get($item->value, 'statistics_number_four'))"
            />
        </div>
        <div class="col-md-6">
            <x-input-field
            name="statistics_content_four"
            label="Nội Dung Cột 4"
            :value="old('value.statistics_content_four', data_get($item->value, 'statistics_content_four'))"
            />
        </div>
        </div>
    </div>
    </div>
</x-slot>