<x-slot name="team_home">
    <div class="card mb-4 border">
    <div class="card-body">
        <div class="row g-3">
        <div class="col-md-12">
            <x-input-field
            name="team_title"
            label="Tiêu Đề"
            :value="old('value.team_title', data_get($item->value, 'team_title'))"
            />
        </div>
        <div class="col-md-6">
            <x-input-field
            name="team_text_button"
            label="Nội Dung Button"
            :value="old('value.team_text_button', data_get($item->value, 'team_text_button'))"
            />
        </div>
        <div class="col-md-6">
            <x-input-field
            name="team_link_button"
            label="Link Button"
            :value="old('value.team_link_button', data_get($item->value, 'team_link_button'))"
            />
        </div>
        </div>
    </div>
    </div>
</x-slot>