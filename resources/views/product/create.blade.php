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
          ['id' => 'seo',  'title' => 'Nội Dung']
        ]">
                <x-slot name="home">
                    <x-input-field
                        name="name"
                        label="Tên sản phẩm"
                        :required="true"
                        :value="old('name')" />

                    <x-input-field
                        name="slug"
                        label="Slug"
                        :required="true"
                        :value="old('slug')" />

                    <x-select-field
                        name="category_id"
                        label="Danh mục"
                        :options="$categories"
                        :value="old('category_id')"
                        id="category_id"
                        placeholder="Chọn danh mục" />

                    <x-input-field
                        name="price"
                        label="Giá"
                        type="number"
                        :value="old('price')" />
                    <x-input-field
                        name="price_discount"
                        label="Giá khuyến mãi"
                        type="number"
                        :value="old('price_discount')" />

                    <x-textarea-field
                        name="description"
                        label="Mô tả"
                        rows="3"
                        :value="old('description')" />

                    <div class="product-file-field mb-6">
                        <x-file-input
                            label="Ảnh đại diện"
                            name="image"
                            :multiple="false" />
                    </div>
                </x-slot>

                <x-slot name="seo">
                    <x-ckeditor
                        name="content"
                        label="Nội dung"
                        :value="old('content')" />
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