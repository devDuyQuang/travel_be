@extends('index')

@section('title', 'Cập nhật Sản Phẩm')

@section('content')
@include('partials.css.product')

<main class="main-wrapper product-form-page">
    <div class="main-content">
        <div class="product-page-shell">

            {{-- Page header --}}
            <div class="product-page-header">
                <div class="product-page-heading">
                    <div class="product-page-icon">
                        <span class="material-icons-outlined">
                            edit_note
                        </span>
                    </div>

                    <div>
                        <h4 class="product-page-title">
                            Cập nhật sản phẩm
                        </h4>

                        <p class="product-page-subtitle">
                            Chỉnh sửa thông tin, hình ảnh và nội dung của
                            {{ $item->name }}.
                        </p>
                    </div>
                </div>

                <a
                    href="{{ panel_route(module().'.index') }}"
                    class="btn product-back-btn"
                >
                    <span class="material-icons-outlined">
                        arrow_back
                    </span>

                    Quay lại
                </a>
            </div>

            {{-- Form card --}}
            <div class="product-form-card">
                <div class="product-form-card-header">
                    <div>
                        <h6 class="product-form-card-title">
                            Thông tin sản phẩm
                        </h6>

                        <p class="product-form-card-description">
                            Kiểm tra nội dung trước khi cập nhật sản phẩm.
                        </p>
                    </div>
                </div>

                <div class="product-form-card-body">
                    <div
                        id="ajax-alert"
                        class="alert"
                        role="alert"
                        style="display: none"
                    ></div>

                    <x-tab-form
                        :action="panel_route(module().'.update', $item->id)"
                        :index-url="panel_route(module().'.index')"
                        method="PUT"
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

                            {{-- THÔNG TIN CƠ BẢN --}}
                            <div class="product-section mb-4">
                                <div class="product-section-heading">
                                    <h6 class="product-section-title">
                                        Thông tin cơ bản
                                    </h6>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <x-input-field
                                            name="name"
                                            label="Tên sản phẩm"
                                            :required="true"
                                            :value="old('name', $item->name)"
                                        />
                                    </div>

                                    <div class="col-md-6">
                                        <x-input-field
                                            name="slug"
                                            label="Slug"
                                            :required="true"
                                            :value="old('slug', $item->slug)"
                                        />
                                    </div>

                                    <div class="col-md-6">
                                        <label
                                            for="category_id"
                                            class="form-label"
                                        >
                                            Danh mục dịch vụ
                                            <span class="text-danger">*</span>
                                        </label>

                                        <select
                                            name="category_id"
                                            id="category_id"
                                            class="form-select"
                                            required
                                        >
                                            <option value="">
                                                Chọn danh mục dịch vụ
                                            </option>

                                            @foreach($categories as $categoryId => $categoryName)
                                                <option
                                                    value="{{ $categoryId }}"
                                                    data-layout-key="{{ $categoryLayouts[$categoryId] ?? '' }}"
                                                    @selected(
                                                        old(
                                                            'category_id',
                                                            $item->category_id
                                                        ) == $categoryId
                                                    )
                                                >
                                                    {{ $categoryName }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('category_id')
                                            <div class="text-danger small mt-1">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <x-input-field
                                            name="badge_text"
                                            label="Nhãn hiển thị"
                                            :value="old(
                                                'badge_text',
                                                $item->badge_text
                                            )"
                                            placeholder="VD: New"
                                        />
                                    </div>

                                    <div class="col-md-4">
                                        <x-input-field
                                            name="order_position"
                                            label="Thứ tự hiển thị"
                                            type="number"
                                            :value="old(
                                                'order_position',
                                                $item->order_position
                                            )"
                                        />
                                    </div>

                                    <div class="col-md-4">
                                        <input
                                            type="hidden"
                                            name="status"
                                            value="0"
                                        >

                                        <div class="product-switch-field">
                                            <label
                                                class="form-label mb-0"
                                                for="status"
                                            >
                                                Đang hiển thị
                                            </label>

                                            <div class="form-check form-switch mb-0">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    name="status"
                                                    id="status"
                                                    value="1"
                                                    @checked(
                                                        old(
                                                            'status',
                                                            $item->status
                                                        )
                                                    )
                                                >
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <input
                                            type="hidden"
                                            name="is_featured"
                                            value="0"
                                        >

                                        <div class="product-switch-field">
                                            <label
                                                class="form-label mb-0"
                                                for="is_featured"
                                            >
                                                Sản phẩm nổi bật
                                            </label>

                                            <div class="form-check form-switch mb-0">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    name="is_featured"
                                                    id="is_featured"
                                                    value="1"
                                                    @checked(
                                                        old(
                                                            'is_featured',
                                                            $item->is_featured
                                                        )
                                                    )
                                                >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- THÔNG TIN CARD --}}
                            <div class="product-section mb-4">
                                <div class="product-section-heading">
                                    <h6 class="product-section-title">
                                        Thông tin card
                                    </h6>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label
                                            for="review_rating"
                                            class="form-label"
                                        >
                                            Điểm đánh giá
                                        </label>

                                        <input
                                            type="number"
                                            name="review_rating"
                                            id="review_rating"
                                            class="form-control"
                                            min="0"
                                            max="5"
                                            step="0.1"
                                            value="{{ old(
                                                'review_rating',
                                                $item->review_rating
                                            ) }}"
                                            placeholder="VD: 4.4"
                                        >

                                        @error('review_rating')
                                            <div class="text-danger small mt-1">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <x-input-field
                                            name="location"
                                            label="Địa điểm"
                                            :value="old(
                                                'location',
                                                $item->location
                                            )"
                                        />
                                    </div>

                                    <div class="col-md-4">
                                        <x-input-field
                                            name="duration"
                                            label="Thời lượng"
                                            :value="old(
                                                'duration',
                                                $item->duration
                                            )"
                                        />
                                    </div>

                                    <div class="col-md-4">
                                        <x-input-field
                                            name="price"
                                            label="Giá"
                                            type="number"
                                            :value="old(
                                                'price',
                                                $item->price
                                            )"
                                        />
                                    </div>

                                    <div class="col-md-4">
                                        <x-input-field
                                            name="price_discount"
                                            label="Giá khuyến mãi"
                                            type="number"
                                            :value="old(
                                                'price_discount',
                                                $item->price_discount
                                            )"
                                        />
                                    </div>

                                    <div class="col-md-4">
                                        <x-input-field
                                            name="review_count"
                                            label="Số lượt đánh giá"
                                            :value="old(
                                                'review_count',
                                                $item->review_count
                                            )"
                                        />
                                    </div>

                                    <div class="col-12">
                                        <x-textarea-field
                                            name="description"
                                            label="Mô tả ngắn"
                                            rows="4"
                                            :value="old(
                                                'description',
                                                $item->description
                                            )"
                                        />
                                    </div>
                                </div>
                            </div>

                            {{-- NỘI DUNG HIỂN THỊ --}}
                            <div class="product-section mb-4">
                                <div class="product-section-heading">
                                    <h6 class="product-section-title">
                                        Nội dung hiển thị
                                    </h6>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <x-textarea-field
                                            name="highlight"
                                            label="Điểm nổi bật"
                                            rows="4"
                                            :value="old(
                                                'highlight',
                                                $item->highlight
                                            )"
                                        />
                                    </div>

                                    <div class="col-md-12">
                                        <x-textarea-field
                                            name="facility"
                                            label="Tiện ích"
                                            rows="4"
                                            :value="old(
                                                'facility',
                                                $item->facility
                                            )"
                                        />
                                    </div>
                                </div>
                            </div>

                            {{-- THÔNG TIN CHUYÊN BIỆT --}}
                            @include(
                                'product.partials.category-attributes',
                                ['item' => $item]
                            )

                            {{-- HÌNH ẢNH VÀ VIDEO --}}
                            <div class="product-section mb-4">
                                <div class="product-section-heading">
                                    <h6 class="product-section-title">
                                        Hình ảnh & Video
                                    </h6>
                                </div>

                                <div class="row g-4">

                                    {{-- Ảnh đại diện --}}
                                    <div class="col-md-6">
                                        <div class="product-media-card">
                                            <label class="form-label fw-semibold">
                                                Ảnh đại diện
                                            </label>

                                            @if(!empty($item->image))
                                                <div class="mb-3">
                                                    <img
                                                        src="{{ Storage::url($item->image) }}"
                                                        alt="Ảnh đại diện"
                                                        class="product-media-preview"
                                                    >
                                                </div>
                                            @endif

                                            <div class="custom-file-row">
                                                <label class="custom-file-btn">
                                                    Chọn tệp

                                                    <input
                                                        type="file"
                                                        name="image"
                                                        class="custom-file-input"
                                                        accept="image/*"
                                                        hidden
                                                    >
                                                </label>

                                                <span class="custom-file-name">
                                                    Không có tệp nào được chọn
                                                </span>
                                            </div>

                                            @error('image')
                                                <div class="text-danger small mt-2">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Video URL --}}
                                    <div class="col-md-6">
                                        <div class="product-media-card">
                                            <label class="form-label fw-semibold">
                                                Video URL
                                            </label>

                                            <input
                                                type="text"
                                                name="video_url"
                                                class="form-control"
                                                value="{{ old(
                                                    'video_url',
                                                    $item->video_url
                                                ) }}"
                                                placeholder="VD: https://www.youtube.com/watch?v=xxxx"
                                            >

                                            @error('video_url')
                                                <div class="text-danger small mt-2">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Ảnh phụ 1 --}}
                                    <div class="col-md-6">
                                        <div class="product-media-card">
                                            <label class="form-label fw-semibold">
                                                Ảnh phụ 1
                                            </label>

                                            @if(!empty($item->gallery_image_1))
                                                <div class="mb-3">
                                                    <img
                                                        src="{{ Storage::url(
                                                            $item->gallery_image_1
                                                        ) }}"
                                                        alt="Ảnh phụ 1"
                                                        class="product-media-preview"
                                                    >
                                                </div>
                                            @endif

                                            <div class="custom-file-row">
                                                <label class="custom-file-btn">
                                                    Chọn tệp

                                                    <input
                                                        type="file"
                                                        name="gallery_image_1"
                                                        class="custom-file-input"
                                                        accept="image/*"
                                                        hidden
                                                    >
                                                </label>

                                                <span class="custom-file-name">
                                                    Không có tệp nào được chọn
                                                </span>
                                            </div>

                                            @error('gallery_image_1')
                                                <div class="text-danger small mt-2">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Ảnh phụ 2 --}}
                                    <div class="col-md-6">
                                        <div class="product-media-card">
                                            <label class="form-label fw-semibold">
                                                Ảnh phụ 2
                                            </label>

                                            @if(!empty($item->gallery_image_2))
                                                <div class="mb-3">
                                                    <img
                                                        src="{{ Storage::url(
                                                            $item->gallery_image_2
                                                        ) }}"
                                                        alt="Ảnh phụ 2"
                                                        class="product-media-preview"
                                                    >
                                                </div>
                                            @endif

                                            <div class="custom-file-row">
                                                <label class="custom-file-btn">
                                                    Chọn tệp

                                                    <input
                                                        type="file"
                                                        name="gallery_image_2"
                                                        class="custom-file-input"
                                                        accept="image/*"
                                                        hidden
                                                    >
                                                </label>

                                                <span class="custom-file-name">
                                                    Không có tệp nào được chọn
                                                </span>
                                            </div>

                                            @error('gallery_image_2')
                                                <div class="text-danger small mt-2">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Ảnh thư viện --}}
                                    <div class="col-md-12">
                                        <div class="product-media-card">
                                            <label class="form-label fw-semibold">
                                                Ảnh thư viện, tối đa 10 ảnh
                                            </label>

                                            @if(
                                                $item->images &&
                                                $item->images->count()
                                            )
                                                <div class="product-gallery-preview mb-3">
                                                    @foreach(
                                                        $item->images as $galleryImage
                                                    )
                                                        <div class="product-gallery-preview-item">
                                                            <img
                                                                src="{{ Storage::url(
                                                                    $galleryImage->image
                                                                ) }}"
                                                                alt="{{ $galleryImage->original_name ?? 'Gallery image' }}"
                                                            >

                                                            <button
                                                                type="button"
                                                                class="product-gallery-delete-btn"
                                                                onclick="
                                                                    if (
                                                                        confirm(
                                                                            'Xóa ảnh này?'
                                                                        )
                                                                    ) {
                                                                        document
                                                                            .getElementById(
                                                                                'delete-gallery-image-{{ $galleryImage->id }}'
                                                                            )
                                                                            .submit();
                                                                    }
                                                                "
                                                            >
                                                                ×
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <div class="custom-file-row">
                                                <label class="custom-file-btn">
                                                    Chọn tệp

                                                    <input
                                                        type="file"
                                                        name="gallery_images[]"
                                                        class="custom-file-input"
                                                        accept="image/*"
                                                        multiple
                                                        hidden
                                                    >
                                                </label>

                                                <span class="custom-file-name">
                                                    Không có tệp nào được chọn
                                                </span>
                                            </div>

                                            @error('gallery_images')
                                                <div class="text-danger small mt-2">
                                                    {{ $message }}
                                                </div>
                                            @enderror

                                            @error('gallery_images.*')
                                                <div class="text-danger small mt-2">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- NỘI DUNG CHI TIẾT --}}
                            <div class="product-section mb-4">
                                <div class="product-section-heading">
                                    <h6 class="product-section-title">
                                        Nội dung chi tiết
                                    </h6>
                                </div>

                                <x-ckeditor
                                    name="content"
                                    label="Nội dung chi tiết"
                                    :value="old(
                                        'content',
                                        $item->content
                                    )"
                                />
                            </div>
                        </x-slot>

                        <x-slot name="seo">
                            <div class="product-section mb-4">
                                <div class="product-section-heading">
                                    <h6 class="product-section-title">
                                        Thông tin SEO
                                    </h6>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <x-input-field
                                            name="title_seo"
                                            label="Tiêu đề SEO"
                                            :value="old(
                                                'title_seo',
                                                $item->title_seo
                                            )"
                                        />
                                    </div>

                                    <div class="col-md-6">
                                        <x-input-field
                                            name="canonical_url"
                                            label="Canonical URL"
                                            :value="old(
                                                'canonical_url',
                                                $item->canonical_url
                                            )"
                                        />
                                    </div>

                                    <div class="col-md-12">
                                        <x-textarea-field
                                            name="description_seo"
                                            label="Mô tả SEO"
                                            rows="4"
                                            :value="old(
                                                'description_seo',
                                                $item->description_seo
                                            )"
                                        />
                                    </div>
                                </div>
                            </div>
                        </x-slot>

                        <x-submit-buttons
                            :cancel-route="panel_route(module().'.index')"
                            submit-text="Cập nhật sản phẩm"
                            cancel-text="Hủy"
                        />
                    </x-tab-form>

                    {{-- Form xóa từng ảnh gallery --}}
                    @if(
                        $item->images &&
                        $item->images->count()
                    )
                        @foreach($item->images as $galleryImage)
                            <form
                                id="delete-gallery-image-{{ $galleryImage->id }}"
                                action="{{ panel_route(
                                    'product.gallery-image.destroy',
                                    ['image' => $galleryImage->id]
                                ) }}"
                                method="POST"
                                class="d-none"
                            >
                                @csrf
                                @method('DELETE')
                            </form>
                        @endforeach
                    @endif
                </div>
            </div>

        </div>
    </div>
</main>

@include('partials.js.product')
@endsection
