@php
    $storedAttributes =
        isset($item) &&
        is_array($item->attributes)
            ? $item->attributes
            : [];
@endphp

<div
    class="product-section mb-4"
    id="product-attributes-section"
>
    <div class="product-section-heading">
        <div>
            <h6 class="product-section-title">
                Thông tin chuyên biệt
            </h6>

            <p class="product-section-description">
                Các trường dưới đây thay đổi theo danh mục dịch vụ.
                Dữ liệu cũ vẫn được bảo toàn khi đổi danh mục.
            </p>
        </div>
    </div>

    @foreach($attributeGroups ?? [] as $layoutKey => $group)
        <div
            class="product-attribute-group d-none"
            data-layout-key="{{ $layoutKey }}"
        >
            <h6 class="product-attribute-group-title">
                {{ $group['title'] }}
            </h6>

            <div class="row g-3">
                @foreach($group['fields'] as $key => $field)
                    @php
                        $value = old(
                            "attributes.$key",
                            $storedAttributes[$key] ?? null
                        );

                        $inputId =
                            "attribute_{$layoutKey}_{$key}";

                        $columnClass =
                            $field['type'] === 'textarea'
                                ? 'col-12'
                                : 'col-md-6';
                    @endphp

                    <div class="{{ $columnClass }}">
                        @if($field['type'] === 'boolean')
                            <input
                                type="hidden"
                                name="attributes[{{ $key }}]"
                                value="0"
                                disabled
                            >

                            <div class="product-switch-field">
                                <div>
                                    <label
                                        class="form-label mb-1"
                                        for="{{ $inputId }}"
                                    >
                                        {{ $field['label'] }}
                                    </label>

                                    @if(!empty($field['description']))
                                        <p class="product-field-help">
                                            {{ $field['description'] }}
                                        </p>
                                    @endif
                                </div>

                                <div class="form-check form-switch mb-0">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="attributes[{{ $key }}]"
                                        id="{{ $inputId }}"
                                        value="1"
                                        disabled
                                        @checked(
                                            filter_var(
                                                $value,
                                                FILTER_VALIDATE_BOOLEAN
                                            )
                                        )
                                    >
                                </div>
                            </div>
                        @elseif($field['type'] === 'textarea')
                            <label
                                class="form-label"
                                for="{{ $inputId }}"
                            >
                                {{ $field['label'] }}
                            </label>

                            <textarea
                                class="form-control"
                                rows="4"
                                name="attributes[{{ $key }}]"
                                id="{{ $inputId }}"
                                disabled
                            >{{ $value }}</textarea>
                        @else
                            <label
                                class="form-label"
                                for="{{ $inputId }}"
                            >
                                {{ $field['label'] }}
                            </label>

                            <input
                                class="form-control"
                                type="{{ $field['type'] }}"
                                name="attributes[{{ $key }}]"
                                id="{{ $inputId }}"
                                value="{{ $value }}"
                                @if(isset($field['min']))
                                    min="{{ $field['min'] }}"
                                @endif
                                disabled
                            >
                        @endif

                        @if(!empty($field['description']))
                            <p class="product-field-help">
                                {{ $field['description'] }}
                            </p>
                        @endif

                        @error("attributes.$key")
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    <div
        class="product-attributes-empty"
        id="product-attributes-empty"
    >
        <span class="material-icons-outlined">
            tune
        </span>

        <div>
            <strong>Chưa chọn danh mục dịch vụ</strong>

            <p>
                Chọn danh mục để hiển thị các trường chuyên biệt.
            </p>
        </div>
    </div>
</div>
