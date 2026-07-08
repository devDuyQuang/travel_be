@php
    $typeLabels = [
        'room_type' => 'Loại phòng',
        'vehicle_type' => 'Loại xe',
        'tour_package' => 'Gói tour',
        'ticket_type' => 'Loại vé',
        'golf_package' => 'Gói golf',
        'time_slot' => 'Khung giờ',
        'consultation_scope' => 'Phạm vi tư vấn',
    ];

    $existingOptions = old('service_options');

    if (! is_array($existingOptions)) {
        $existingOptions = isset($item) && $item->relationLoaded('serviceOptions')
            ? $item->serviceOptions->map(fn ($option) => [
                'id' => $option->id,
                'type' => $option->type,
                'name' => $option->name,
                'description' => $option->description,
                'price' => $option->price,
                'currency' => $option->currency,
                'unit' => $option->unit,
                'capacity' => $option->capacity,
                'sort_order' => $option->sort_order,
                'is_active' => $option->is_active ? '1' : '0',
                '_delete' => '0',
            ])->values()->all()
            : [];
    }
@endphp

<div class="product-section mb-4" id="service-options-section">
    <div class="product-section-heading">
        <div>
            <h6 class="product-section-title">
                Tùy chọn dịch vụ & giá
            </h6>

            <p class="product-section-description">
                Quản lý loại phòng, loại xe, gói tour, vé, khung giờ hoặc gói golf để frontend tính giá chính xác hơn.
            </p>
        </div>

        <button
            type="button"
            class="btn btn-sm btn-outline-primary js-add-service-option"
        >
            + Thêm tùy chọn
        </button>
    </div>

    <div
        class="service-options-empty alert alert-light border {{ count($existingOptions) ? 'd-none' : '' }}"
        data-service-options-empty
    >
        Chưa có tùy chọn dịch vụ. Bấm "+ Thêm tùy chọn" để thêm giá theo loại phòng, xe, gói tour...
    </div>

    <div class="service-options-list" data-service-options-list>
        @foreach($existingOptions as $index => $option)
            @include('product.partials.service-option-row', [
                'index' => $index,
                'option' => $option,
                'serviceOptionTypes' => $serviceOptionTypes ?? [],
                'serviceOptionUnits' => $serviceOptionUnits ?? [],
                'typeLabels' => $typeLabels,
            ])
        @endforeach
    </div>

    <template data-service-option-template>
        @include('product.partials.service-option-row', [
            'index' => '__INDEX__',
            'option' => [],
            'serviceOptionTypes' => $serviceOptionTypes ?? [],
            'serviceOptionUnits' => $serviceOptionUnits ?? [],
            'typeLabels' => $typeLabels,
        ])
    </template>
</div>
