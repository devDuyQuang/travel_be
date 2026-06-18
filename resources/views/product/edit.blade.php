@extends('index')
@section('title', 'Cập nhật Sản Phẩm')

@section('content')
@include('partials.css.product')

<main class="main-wrapper product-form-page">
    <div class="main-content">
        <h5 class="card-header product-form-title">
            Cập nhật Sản Phẩm: {{ $item->name }}
        </h5>

        <div class="card-body product-form-card">
            <div id="ajax-alert" style="display:none" class="alert" role="alert"></div>
            <x-tab-form
                :action="panel_route(module().'.update', $item->id)"
                :index-url="panel_route(module().'.index')"
                method="PUT"
                :tabs="[
          ['id' => 'home', 'title' => 'Thông Tin Chung', 'active' => true],
          ['id' => 'seo',  'title' => 'Thẻ SEO']
        ]">
                <x-slot name="home">
                    {{-- THÔNG TIN CƠ BẢN --}}
                    <div class="product-section mb-4">
                        <h6 class="product-section-title mb-3">Thông tin cơ bản</h6>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <x-input-field
                                    name="name"
                                    label="Tên sản phẩm"
                                    :required="true"
                                    :value="old('name', $item->name)" />
                            </div>

                            <div class="col-md-6">
                                <x-input-field
                                    name="slug"
                                    label="Slug"
                                    :required="true"
                                    :value="old('slug', $item->slug)" />
                            </div>

                            <div class="col-md-6">
                                <label for="category_id" class="form-label">Danh mục</label>

                                <select name="category_id" id="category_id" class="form-select">
                                    <option value="">Chọn danh mục</option>

                                    @foreach($categories as $categoryId => $categoryName)
                                    <option value="{{ $categoryId }}" {{ old('category_id', $item->category_id) == $categoryId ? 'selected' : '' }}>
                                        {{ $categoryName }}
                                    </option>
                                    @endforeach
                                </select>

                                @error('category_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <x-input-field
                                    name="badge_text"
                                    label="Nhãn hiển thị"
                                    :value="old('badge_text', $item->badge_text)"
                                    placeholder="VD: New" />
                            </div>
                        </div>
                    </div>

                    {{-- GIÁ & HIỂN THỊ CARD --}}
                    <div class="product-section mb-4">
                        <h6 class="product-section-title mb-3">Giá & thông tin hiển thị</h6>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="star_rating" class="form-label">Số sao hiển thị</label>
                                <input
                                    type="number"
                                    name="star_rating"
                                    id="star_rating"
                                    class="form-control"
                                    min="0"
                                    max="5"
                                    step="0.1"
                                    value="{{ old('star_rating', $item->star_rating ?? 4.4) }}"
                                    placeholder="VD: 4.4">

                                @error('star_rating')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="col-md-4">
                                <x-input-field
                                    name="location"
                                    label="Địa điểm"
                                    :value="old('location', $item->location)" />
                            </div>

                            <div class="col-md-4">
                                <x-input-field
                                    name="duration"
                                    label="Thời lượng"
                                    :value="old('duration', $item->duration)" />
                            </div>
                        </div>
                    </div>

                    {{-- THÔNG TIN SÂN GOLF --}}
                    <div class="product-section mb-4">
                        <h6 class="product-section-title mb-3">Thông tin sân golf</h6>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <x-input-field
                                    name="review_count"
                                    label="Google reviews count"
                                    :value="old('review_count', $item->review_count ?? '')" />
                            </div>

                            <div class="col-md-6">
                                <x-input-field
                                    name="established_text"
                                    label="Established text"
                                    :value="old('established_text', $item->established_text ?? '')"
                                    placeholder="VD: Established : 2018" />
                            </div>
                        </div>
                    </div>

                    {{-- NỘI DUNG NGẮN --}}
                    <div class="product-section mb-4">
                        <h6 class="product-section-title mb-3">Nội dung hiển thị</h6>

                        <div class="row g-3">
                            <div class="col-md-12">
                                <x-ckeditor
                                    name="golf_information"
                                    label="Thông tin bảng sân golf"
                                    :value="old('golf_information', $item->golf_information)" />
                            </div>

                            <div class="col-md-12">
                                <x-textarea-field
                                    name="highlight"
                                    label="Điểm nổi bật của sân"
                                    rows="4"
                                    :value="old('highlight', $item->highlight)" />
                            </div>


                        </div>
                    </div>

                    {{-- HÌNH ẢNH & VIDEO --}}
                    <div class="product-section mb-4">
                        <h6 class="product-section-title mb-3">Hình ảnh & Video</h6>

                        <div class="row g-4">
                            {{-- Ảnh đại diện --}}
                            <div class="col-md-6">
                                <div class="product-media-card">
                                    <label class="form-label fw-semibold">Ảnh đại diện</label>

                                    @if (!empty($item->image))
                                    <div class="mb-2">
                                        <img
                                            src="{{ Storage::url($item->image) }}"
                                            alt="Ảnh đại diện"
                                            class="product-media-preview">
                                    </div>
                                    @endif

                                    <div class="custom-file-row">
                                        <label class="custom-file-btn">
                                            Chọn tệp
                                            <input type="file" name="image" class="custom-file-input" accept="image/*" hidden>
                                        </label>

                                       
                                    </div>
                                </div>
                            </div>

                            {{-- Video URL --}}
                            <div class="col-md-6">
                                <div class="product-media-card">
                                    <label class="form-label fw-semibold">Video URL</label>

                                    <input
                                        type="text"
                                        name="video_url"
                                        class="form-control"
                                        value="{{ old('video_url', $item->video_url) }}"
                                        placeholder="VD: https://www.youtube.com/watch?v=xxxx">
                                </div>
                            </div>

                            {{-- Ảnh phụ 1 --}}
                            <div class="col-md-6">
                                <div class="product-media-card">
                                    <label class="form-label fw-semibold">Ảnh phụ 1</label>

                                    @if (!empty($item->gallery_image_1))
                                    <div class="mb-2">
                                        <img
                                            src="{{ Storage::url($item->gallery_image_1) }}"
                                            alt="Ảnh phụ 1"
                                            class="product-media-preview">
                                    </div>
                                    @endif

                                    <div class="custom-file-row">
                                        <label class="custom-file-btn">
                                            Chọn tệp
                                            <input type="file" name="gallery_image_1" class="custom-file-input" accept="image/*" hidden>
                                        </label>

                                       
                                    </div>
                                </div>
                            </div>

                            {{-- Ảnh phụ 2 --}}
                            <div class="col-md-6">
                                <div class="product-media-card">
                                    <label class="form-label fw-semibold">Ảnh phụ 2</label>

                                    @if (!empty($item->gallery_image_2))
                                    <div class="mb-2">
                                        <img
                                            src="{{ Storage::url($item->gallery_image_2) }}"
                                            alt="Ảnh phụ 2"
                                            class="product-media-preview">
                                    </div>
                                    @endif

                                    <div class="custom-file-row">
                                        <label class="custom-file-btn">
                                            Chọn tệp
                                            <input type="file" name="gallery_image_2" class="custom-file-input" accept="image/*" hidden>
                                        </label>

                                        
                                    </div>
                                </div>
                            </div>

                            {{-- Ảnh thư viện --}}
                            <div class="col-md-12">
                                <div class="product-media-card">
                                    <label class="form-label fw-semibold">Ảnh thư viện, tối đa 10 ảnh</label>

                                    @if($item->images && $item->images->count())
                                    <div class="product-gallery-preview mb-3">
                                        @foreach($item->images as $galleryImage)
                                        <div class="product-gallery-preview-item">
                                            <img
                                                src="{{ Storage::url($galleryImage->image) }}"
                                                alt="{{ $galleryImage->original_name ?? 'Gallery image' }}">

                                            <button
                                                type="button"
                                                class="product-gallery-delete-btn"
                                                onclick="if(confirm('Xóa ảnh này?')) document.getElementById('delete-gallery-image-{{ $galleryImage->id }}').submit();">
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
                                                hidden>
                                        </label>

                                    
                                    </div>

                                    @error('gallery_images')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror

                                    @error('gallery_images.*')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- NỘI DUNG CHI TIẾT --}}
                    <div class="product-section mb-4">
                        <h6 class="product-section-title mb-3">Nội dung chi tiết</h6>

                        <x-ckeditor
                            name="content"
                            label="Nội dung"
                            :value="old('content', $item->content)" />
                    </div>
                </x-slot>

                <x-slot name="seo">
                    <div class="product-section mb-4">
                        <h6 class="product-section-title mb-3">Thông tin SEO</h6>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <x-input-field
                                    name="title_seo"
                                    label="Title SEO"
                                    :value="old('title_seo')" />
                            </div>

                            <div class="col-md-6">
                                <x-input-field
                                    name="canonical_url"
                                    label="Canonical URL"
                                    :value="old('canonical_url')" />
                            </div>

                            <div class="col-md-12">
                                <x-textarea-field
                                    name="description_seo"
                                    label="Description SEO"
                                    rows="4"
                                    :value="old('description_seo')" />
                            </div>
                        </div>
                    </div>
                </x-slot>

                <x-submit-buttons
                    :cancel-route="panel_route(module().'.index')"
                    submit-text="Cập nhật"
                    cancel-text="Thoát" />
            </x-tab-form>
            @if($item->images && $item->images->count())
            @foreach($item->images as $galleryImage)
            <form
                id="delete-gallery-image-{{ $galleryImage->id }}"
                action="{{ panel_route('product.gallery-image.destroy', ['image' => $galleryImage->id]) }}"
                method="POST"
                style="display:none;">
                @csrf
                @method('DELETE')
            </form>
            @endforeach
            @endif

        </div>
    </div>
    <style>
        .custom-file-row {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
        }

        .custom-file-btn {
            margin: 0;
            padding: 9px 18px;
            border-radius: 6px;
            background: #2f3338;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            border: 1px solid #444;
        }

        .custom-file-btn:hover {
            background: #3b4046;
        }
    </style>

</main>

<script>
    document.addEventListener('change', function(event) {
        const input = event.target;

        if (!input.classList.contains('custom-file-input')) {
            return;
        }

        const file = input.files && input.files[0] ? input.files[0] : null;
        const fileName = file ? file.name : 'Không có tệp nào được chọn';

        const wrapper = input.closest('.custom-file-row');

        if (!file || !file.type.startsWith('image/')) {
            return;
        }

        const mediaCard = input.closest('.product-media-card');

        if (!mediaCard) {
            return;
        }

        let img = mediaCard.querySelector('img');

        if (!img) {
            img = document.createElement('img');
            img.style.width = '220px';
            img.style.height = '130px';
            img.style.objectFit = 'cover';
            img.style.borderRadius = '8px';
            img.style.border = '1px solid #444';

            const previewWrapper = document.createElement('div');
            previewWrapper.className = 'mb-2';
            previewWrapper.appendChild(img);

            mediaCard.insertBefore(previewWrapper, mediaCard.querySelector('.custom-file-row'));
        }

        img.src = URL.createObjectURL(file);
    });
</script>

<style>
    .product-form-page .cke {
        width: 100% !important;
    }

    .product-form-page .cke_contents {
        min-height: 430px !important;
    }

    .product-form-page .cke_wysiwyg_frame,
    .product-form-page .cke_wysiwyg_div {
        min-height: 430px !important;
    }

    .product-form-page .cke_editable {
        font-size: 15px !important;
        line-height: 1.7 !important;
        padding: 18px !important;
    }

    .product-form-page table {
        width: 100% !important;
    }

    .product-form-page table td,
    .product-form-page table th {
        padding: 8px 12px !important;
        vertical-align: top;
    }

    .product-media-preview {
    width: 260px;
    height: 150px;
    object-fit: cover;
    border-radius: 12px;
    border: 1px solid #d1d5db;
}

.custom-file-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #ffffff !important;
    color: #111827 !important;
    border: 1px solid #111827 !important;
    border-radius: 10px;
    padding: 10px 22px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    transition: all .2s ease;
}

.custom-file-btn:hover,
.custom-file-btn:focus,
.custom-file-btn:active {
    background: #111827 !important;
    color: #ffffff !important;
    border-color: #111827 !important;
    box-shadow: none !important;
}

.custom-file-btn * {
    color: inherit !important;
}
</style>


@endsection