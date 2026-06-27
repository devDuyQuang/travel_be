@extends('index')

@section('title', 'Thêm ' . page_title())

@section('content')
@include('partials.css.post')

<main class="main-wrapper post-form-page">
    <div class="main-content">
        <div class="post-page-shell">

            <div class="post-page-header">
                <div class="post-page-heading">
                    <div class="post-page-icon">
                        <span class="material-icons-outlined">
                            post_add
                        </span>
                    </div>

                    <div>
                        <h4 class="post-page-title">
                            Thêm bài viết
                        </h4>

                        <p class="post-page-subtitle">
                            Tạo mới nội dung và thiết lập thông tin SEO cho website.
                        </p>
                    </div>
                </div>

                <a
                    href="{{ panel_route(module().'.index') }}"
                    class="btn post-back-btn"
                >
                    <span class="material-icons-outlined">
                        arrow_back
                    </span>

                    Quay lại
                </a>
            </div>

            <div class="post-form-card">
                <div class="post-form-card-header">
                    <div>
                        <h6 class="post-form-card-title">
                            Thông tin bài viết
                        </h6>

                        <p class="post-form-card-description">
                            Nhập đầy đủ nội dung trước khi lưu bài viết.
                        </p>
                    </div>
                </div>

                <div class="post-form-card-body">
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
                                        label="Tên bài viết"
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

                                <div class="col-12">
                                    <label
                                        for="post-category_ids"
                                        class="form-label"
                                    >
                                        Danh mục
                                    </label>

                                    <select
                                        name="category_ids[]"
                                        id="post-category_ids"
                                        class="select2 form-select"
                                        multiple
                                    >
                                        @foreach($categories ?? [] as $id => $name)
                                            <option
                                                value="{{ $id }}"
                                                @selected(
                                                    in_array(
                                                        $id,
                                                        old('category_ids', [])
                                                    )
                                                )
                                            >
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label for="post-tag_ids" class="form-label">Thẻ bài viết</label>
                                    <select name="tag_ids[]" id="post-tag_ids" class="select2 form-select" multiple>
                                        @foreach($tags ?? [] as $id => $name)
                                            <option value="{{ $id }}" @selected(in_array($id, old('tag_ids', [])))>{{ $name }}</option>
                                        @endforeach
                                    </select>
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

                                <div class="col-12">
                                    <x-file-input
                                        label="Ảnh đại diện"
                                        name="file"
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
                            submit-text="Lưu bài viết"
                            cancel-text="Hủy"
                        />
                    </x-tab-form>
                </div>
            </div>

        </div>
    </div>
</main>

<script>
(function () {
    function bootPostForm() {
        if (!window.jQuery) {
            setTimeout(bootPostForm, 100);
            return;
        }

        var $ = window.jQuery;
        var $categorySelect = $('#post-category_ids');
        var $tagSelect = $('#post-tag_ids');

        if (
            $categorySelect.length &&
            typeof $.fn.select2 === 'function' &&
            !$categorySelect.hasClass('select2-hidden-accessible')
        ) {
            $categorySelect.select2({
                width: '100%',
                placeholder: 'Chọn danh mục',
                closeOnSelect: false
            });
        }

        if (
            $tagSelect.length &&
            typeof $.fn.select2 === 'function' &&
            !$tagSelect.hasClass('select2-hidden-accessible')
        ) {
            $tagSelect.select2({
                width: '100%',
                placeholder: 'Chọn thẻ bài viết',
                closeOnSelect: false
            });
        }
    }

    if (document.readyState === 'complete') {
        bootPostForm();
    } else {
        window.addEventListener('load', bootPostForm, {
            once: true
        });
    }
})();
</script>
@endsection
