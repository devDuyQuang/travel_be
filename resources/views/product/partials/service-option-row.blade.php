@php
    $option = is_array($option ?? null) ? $option : [];
    $rowPrefix = "service_options.$index";
    $inputPrefix = "service_options[$index]";
    $isDeleted = filter_var($option['_delete'] ?? false, FILTER_VALIDATE_BOOLEAN);
    $isActive = array_key_exists('is_active', $option)
        ? filter_var($option['is_active'], FILTER_VALIDATE_BOOLEAN)
        : true;
@endphp

<div
    class="service-option-row border rounded p-3 mb-3 {{ $isDeleted ? 'd-none' : '' }}"
    data-service-option-row
>
    <input
        type="hidden"
        name="{{ $inputPrefix }}[id]"
        value="{{ $option['id'] ?? '' }}"
    >

    <input
        type="hidden"
        name="{{ $inputPrefix }}[_delete]"
        value="{{ $isDeleted ? '1' : '0' }}"
        data-service-option-delete
    >

    <div class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label">Loại tùy chọn</label>
            <select
                name="{{ $inputPrefix }}[type]"
                class="form-select"
            >
                @foreach($serviceOptionTypes as $type)
                    <option
                        value="{{ $type }}"
                        @selected(($option['type'] ?? '') === $type)
                    >
                        {{ $typeLabels[$type] ?? $type }}
                    </option>
                @endforeach
            </select>
            @error("$rowPrefix.type")
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">Tên tùy chọn</label>
            <input
                type="text"
                name="{{ $inputPrefix }}[name]"
                class="form-control"
                value="{{ $option['name'] ?? '' }}"
                placeholder="VD: Deluxe Ocean View"
            >
            @error("$rowPrefix.name")
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-2">
            <label class="form-label">Giá</label>
            <input
                type="number"
                min="0"
                step="1000"
                name="{{ $inputPrefix }}[price]"
                class="form-control"
                value="{{ $option['price'] ?? '' }}"
            >
            @error("$rowPrefix.price")
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-2">
            <label class="form-label">Đơn vị</label>
            <select
                name="{{ $inputPrefix }}[unit]"
                class="form-select"
            >
                <option value="">Chọn</option>
                @foreach($serviceOptionUnits as $unit)
                    <option
                        value="{{ $unit }}"
                        @selected(($option['unit'] ?? '') === $unit)
                    >
                        {{ $unit }}
                    </option>
                @endforeach
            </select>
            @error("$rowPrefix.unit")
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-1 text-end">
            <button
                type="button"
                class="btn btn-outline-danger js-remove-service-option"
                title="Xóa tùy chọn"
            >
                Xóa
            </button>
        </div>

        <div class="col-md-12">
            <label class="form-label">Mô tả</label>
            <textarea
                name="{{ $inputPrefix }}[description]"
                class="form-control"
                rows="2"
                placeholder="Thông tin mô tả ngắn cho admin/khách hàng"
            >{{ $option['description'] ?? '' }}</textarea>
            @error("$rowPrefix.description")
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-3">
            <label class="form-label">Sức chứa</label>
            <input
                type="number"
                min="0"
                name="{{ $inputPrefix }}[capacity]"
                class="form-control"
                value="{{ $option['capacity'] ?? '' }}"
            >
        </div>

        <div class="col-md-3">
            <label class="form-label">Thứ tự</label>
            <input
                type="number"
                min="0"
                name="{{ $inputPrefix }}[sort_order]"
                class="form-control"
                value="{{ $option['sort_order'] ?? 0 }}"
            >
        </div>

        <div class="col-md-3">
            <label class="form-label">Tiền tệ</label>
            <input
                type="text"
                name="{{ $inputPrefix }}[currency]"
                class="form-control"
                value="{{ $option['currency'] ?? 'VND' }}"
            >
        </div>

        <div class="col-md-3">
            <input
                type="hidden"
                name="{{ $inputPrefix }}[is_active]"
                value="0"
            >

            <div class="form-check form-switch mt-4">
                <input
                    class="form-check-input"
                    type="checkbox"
                    name="{{ $inputPrefix }}[is_active]"
                    value="1"
                    @checked($isActive)
                >
                <label class="form-check-label">
                    Đang kích hoạt
                </label>
            </div>
        </div>
    </div>
</div>
