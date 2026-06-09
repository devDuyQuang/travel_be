@extends('index')
@section('title', 'Thêm Sản Phẩm')

@section('content')
@include('partials.css.product')

<main class="main-wrapper product-form-page">
    <div class="main-content">
        <h5 class="card-header product-form-title">
            Thêm Sản Phẩm
        </h5>

        <div class="card-body product-form-card">
            <div id="ajax-alert" style="display:none" class="alert" role="alert"></div>

            <x-tab-form
                :action="panel_route(module().'.store')"
                :index-url="panel_route(module().'.index')"
                method="POST"
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
                                    :value="old('name')" />
                            </div>

                            <div class="col-md-6">
                                <x-input-field
                                    name="slug"
                                    label="Slug"
                                    :required="true"
                                    :value="old('slug')" />
                            </div>

                            <div class="col-md-6">
                                <label for="category_id" class="form-label">Danh mục</label>

                                <select name="category_id" id="category_id" class="form-select">
                                    <option value="">Chọn danh mục</option>

                                    @foreach($categories as $categoryId => $categoryName)
                                    <option value="{{ $categoryId }}" {{ old('category_id') == $categoryId ? 'selected' : '' }}>
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
                                    :value="old('badge_text')"
                                    placeholder="VD: New" />
                            </div>
                        </div>
                    </div>

                    {{-- GIÁ & THÔNG TIN HIỂN THỊ --}}
                    <div class="product-section mb-4">
                        <h6 class="product-section-title mb-3">Giá & thông tin hiển thị</h6>

                        <div class="row g-3">
                            <div class="col-md-3">
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

                            <div class="col-md-3">
                                <label for="price" class="form-label">Giá hiển thị</label>
                                <input
                                    type="text"
                                    name="price"
                                    id="price"
                                    class="form-control"
                                    inputmode="decimal"
                                    value="{{ old('price') }}"
                                    placeholder="VD: 59.00">

                                @error('price')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <x-input-field
                                    name="location"
                                    label="Địa điểm"
                                    :value="old('location')" />
                            </div>

                            <div class="col-md-3">
                                <x-input-field
                                    name="duration"
                                    label="Thời lượng"
                                    :value="old('duration')" />
                            </div>
                        </div>
                    </div>

                    {{-- THÔNG TIN SÂN GOLF --}}
                    <div class="product-section mb-4">
                        <h6 class="product-section-title mb-3">Thông tin sân golf</h6>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="review_rating" class="form-label">Google reviews rating</label>
                                <input
                                    type="text"
                                    name="review_rating"
                                    id="review_rating"
                                    class="form-control"
                                    inputmode="decimal"
                                    value="{{ old('review_rating') }}"
                                    placeholder="VD: 4.4 hoặc 4,4">

                                @error('review_rating')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <x-input-field
                                    name="review_count"
                                    label="Google reviews count"
                                    :value="old('review_count')" />
                            </div>

                            <div class="col-md-4">
                                <x-input-field
                                    name="established_year"
                                    label="Established year"
                                    type="number"
                                    :value="old('established_year')" />
                            </div>
                        </div>
                    </div>

                    {{-- NỘI DUNG HIỂN THỊ --}}
                    <div class="product-section mb-4">
                        <h6 class="product-section-title mb-3">Nội dung hiển thị</h6>

                        <div class="row g-3">

                            <div class="col-md-12">
                                <x-ckeditor
                                    name="golf_information"
                                    label="Thông tin bảng sân golf"
                                    :value="old('golf_information')" />
                            </div>

                            <div class="col-md-6">
                                <x-textarea-field
                                    name="highlight"
                                    label="Điểm nổi bật của sân"
                                    rows="4"
                                    :value="old('highlight')" />
                            </div>

                            <div class="col-md-6">
                                <x-textarea-field
                                    name="facility"
                                    label="Dịch vụ tiện ích của sân"
                                    rows="4"
                                    :value="old('facility')" />
                            </div>

                        </div>
                    </div>


                    {{-- ẢNH / VIDEO --}}
                    <div class="product-section mb-4">
                        <h6 class="product-section-title mb-3">Hình ảnh & Video</h6>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Ảnh đại diện</label>

                                <div class="product-file-field">
                                    <x-file-input
                                        label="Ảnh đại diện"
                                        name="image"
                                        :multiple="false" />
                                </div>

                                @error('image')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Video URL</label>

                                <input
                                    type="text"
                                    name="video_url"
                                    class="form-control"
                                    value="{{ old('video_url') }}"
                                    placeholder="VD: https://www.youtube.com/watch?v=xxxx">

                                @error('video_url')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Ảnh phụ 1</label>

                                <div class="product-file-field">
                                    <x-file-input
                                        label="Ảnh phụ 1"
                                        name="gallery_image_1"
                                        :multiple="false" />
                                </div>

                                @error('gallery_image_1')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Ảnh phụ 2</label>

                                <div class="product-file-field">
                                    <x-file-input
                                        label="Ảnh phụ 2"
                                        name="gallery_image_2"
                                        :multiple="false" />
                                </div>

                                @error('gallery_image_2')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Ảnh thư viện, tối đa 10 ảnh</label>

                                <div class="product-gallery-upload">
                                    <label
                                        for="gallery_images"
                                        class="product-gallery-upload-label">
                                        Chọn tệp
                                    </label>

                                    <span
                                        id="gallery-images-name"
                                        class="product-gallery-upload-name">
                                        Không có tệp nào được chọn
                                    </span>

                                    <input
                                        id="gallery_images"
                                        type="file"
                                        name="gallery_images[]"
                                        accept="image/*"
                                        multiple>
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

                    {{-- NỘI DUNG CHI TIẾT --}}
                    <div class="product-section mb-4">
                        <h6 class="product-section-title mb-3">Nội dung chi tiết</h6>

                        <x-ckeditor
                            name="content"
                            label="Nội dung"
                            :value="old('content')" />
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
                    submit-text="Lưu"
                    cancel-text="Thoát" />
            </x-tab-form>
        </div>
    </div>
</main>
@endsection