@extends('index')

@section('title', 'Thêm ' . page_title())

@section('content')
@include('partials.css.category')

<main class="main-wrapper category-form-page">
    <div class="main-content">
        <div class="category-page-shell">

            <div class="category-page-header">
                <div class="category-page-heading">
                    <div class="category-page-icon">
                        <span class="material-icons-outlined">
                            create_new_folder
                        </span>
                    </div>

                    <div>
                        <h4 class="category-page-title">
                            Thêm danh mục
                        </h4>

                        <p class="category-page-subtitle">
                            Tạo nhóm nội dung mới và thiết lập thông tin SEO.
                        </p>
                    </div>
                </div>

                <a
                    href="{{ panel_route(module().'.index') }}"
                    class="btn category-back-btn"
                >
                    <span class="material-icons-outlined">
                        arrow_back
                    </span>

                    Quay lại
                </a>
            </div>

            <div class="category-form-card">
                <div class="category-form-card-header">
                    <div>
                        <h6 class="category-form-card-title">
                            Thông tin danh mục
                        </h6>

                        <p class="category-form-card-description">
                            Nhập đầy đủ dữ liệu trước khi lưu danh mục.
                        </p>
                    </div>
                </div>

                <div class="category-form-card-body">
                    <div
                        id="ajax-alert"
                        class="alert"
                        role="alert"
                        style="display: none"
                    ></div>

                    <x-tab-form
                        :action="panel_route(module().'.store')"
                        :index-url="panel_route(module().'.index')"
                        method="POST"
                        :tabs="[
                            [
                                'id' => 'home',
                                'title' => 'Thông tin chung',
                                'active' => true
                            ],
                            [
                                'id' => 'seo',
                                'title' => 'Thiết lập SEO'
                            ]
                        ]"
                    >
                        <x-slot name="home">
                            <div class="row g-3">

                                <div class="col-lg-8">
                                    <x-input-field
                                        name="name"
                                        label="Tên danh mục"
                                        :required="true"
                                        :value="old('name')"
                                    />
                                </div>

                                <div class="col-lg-4">
                                    <x-input-field
                                        name="slug"
                                        label="Slug"
                                        :required="true"
                                        :value="old('slug')"
                                    />
                                </div>

                                <div class="col-lg-6">
                                    <x-select-field
                                        name="parent_id"
                                        label="Danh mục cha"
                                        :options="$parents"
                                        :value="old('parent_id')"
                                        id="parent_id"
                                        placeholder="Chọn danh mục cha"
                                    />
                                </div>

                                <div class="col-lg-6" id="category-layout-field">
                                    <div class="category-field-group">
                                        <label class="form-label" for="category-layout">
                                            Giao diện hiển thị
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select
                                            name="layout_key"
                                            id="category-layout"
                                            class="form-select no-select2 @error('layout_key') is-invalid @enderror">
                                            <option value="">Chọn giao diện</option>
                                            @foreach($layoutOptions as $value => $label)
                                                <option value="{{ $value }}" @selected(old('layout_key') === $value)>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('layout_key')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="category-field-group">
                                        <label
                                            class="form-label"
                                            for="category-type"
                                        >
                                            Loại danh mục
                                            <span class="text-danger">*</span>
                                        </label>

                                        <select
                                            name="type"
                                            id="category-type"
                                            class="form-select no-select2 @error('type') is-invalid @enderror"
                                            required
                                        >
                                            <option
                                                value="post"
                                                @selected(
                                                    old('type', 'service') === 'post'
                                                )
                                            >
                                                Bài viết
                                            </option>

                                            <option
                                                value="service"
                                                @selected(
                                                    old('type', 'service') === 'service'
                                                )
                                            >
                                                Dịch vụ
                                            </option>
                                        </select>

                                        @error('type')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <x-textarea-field
                                        name="description"
                                        label="Mô tả ngắn"
                                        rows="3"
                                        :value="old('description')"
                                    />
                                </div>

                                <div class="col-12">
                                    <x-ckeditor
                                        name="content"
                                        label="Nội dung"
                                        :value="old('content', '')"
                                    />
                                </div>

                                <div class="col-lg-6">
                                    <x-file-input
                                        label="Ảnh đại diện"
                                        name="file"
                                        :multiple="false"
                                    />
                                </div>

                                <div class="col-lg-6">
                                    <x-file-input
                                        label="Icon danh mục"
                                        name="icon_file"
                                        :multiple="false"
                                    />
                                </div>

                            </div>
                        </x-slot>

                        <x-slot name="seo">
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <x-input-field
                                        name="title_seo"
                                        label="Tiêu đề SEO"
                                        :value="old('title_seo')"
                                    />
                                </div>

                                <div class="col-md-6">
                                    <x-input-field
                                        name="canonical_seo"
                                        label="Canonical URL"
                                        :value="old('canonical_seo')"
                                    />
                                </div>

                                <div class="col-12">
                                    <x-textarea-field
                                        name="description_seo"
                                        label="Mô tả SEO"
                                        :value="old('description_seo')"
                                        rows="4"
                                    />
                                </div>

                            </div>
                        </x-slot>

                        <x-submit-buttons
                            :cancel-route="panel_route(module().'.index')"
                            submit-text="Lưu danh mục"
                            cancel-text="Hủy"
                        />
                    </x-tab-form>
                </div>
            </div>

        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
(function ($) {
    'use strict';

    $(function () {
        var $parentSelect = $('#parent_id');
        var $typeSelect = $('#category-type');
        var $layoutField = $('#category-layout-field');
        var $layoutSelect = $('#category-layout');

        function syncLayoutField() {
            var isService = $typeSelect.val() === 'service';
            $layoutField.toggle(isService);
            $layoutSelect.prop('required', isService);
        }

        if (
            $parentSelect.length &&
            typeof $.fn.select2 === 'function' &&
            !$parentSelect.hasClass('select2-hidden-accessible')
        ) {
            $parentSelect.select2({
                width: '100%',
                placeholder: 'Chọn danh mục cha',
                allowClear: true
            });
        }

        $typeSelect.on('change', syncLayoutField);
        syncLayoutField();
    });
})(jQuery);
</script>
@endpush
