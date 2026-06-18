@extends('index')
@section('title', 'Thêm '. page_title())

@section('content')
@include('partials.css.category')

<main class="main-wrapper category-form-page">
  <div class="main-content">
    <h5 class="card-header category-form-title">
      Thêm {{ page_title() }}
    </h5>

    <div class="card-body category-form-card">
      <div id="ajax-alert" style="display:none" class="alert" role="alert"></div>

      <x-tab-form
        :action="panel_route(module().'.store')"
        :index-url="panel_route(module().'.index')"
        method="POST"
        :tabs="[
          ['id' => 'home', 'title' => 'Thông Tin Chung', 'active' => true],
          ['id' => 'seo',  'title' => 'Thẻ Seo']
        ]">
        <x-slot name="home">
          <x-input-field
            name="name"
            label="Tên"
            :required="true"
            :value="old('name')" />

          <x-input-field
            name="slug"
            label="Slug"
            :required="true"
            :value="old('slug')" />

          <x-select-field
            name="parent_id"
            label="{{ page_title() }} Cha"
            :options="$parents"
            :value="old('parent_id')"
            id="parent_id"
            placeholder="Chọn {{ page_title() }} Cha" />

          <x-textarea-field
            name="description"
            label="Mô Tả"
            rows="3"
            :value="old('description')" />

          <x-ckeditor
            name="content"
            label="Nội Dung"
            :value="old('content', '')" />

          <x-file-input
            label="Ảnh Đại Diện"
            name="file"
            :multiple="false" />

          <x-file-input
            label="Icon"
            name="icon_file"
            :multiple="false" />

          <div class="mb-6">
            <label class="form-label" for="category-type">Loại</label>

            <select
              name="type"
              id="category-type"
              class="form-select @error('type') is-invalid @enderror no-select2"
              required>
              <option value="post" @selected(old('type', 'post' )==='post' )>
                POST
              </option>

              <option value="product" @selected(old('type', 'post' )==='product' )>
                PRODUCT
              </option>

              <option value="service" @selected(old('type', 'post' )==='service' )>
                SERVICE
              </option>
            </select>

            <div class="invalid-feedback" id="error-type">
              @error('type') {{ $message }} @enderror
            </div>
          </div>
        </x-slot>

        <x-slot name="seo">
          <div class="row g-3">
            <div class="col-md-6">
              <x-input-field
                name="title_seo"
                label="Title SEO"
                :value="old('title_seo')" />
            </div>

            <div class="col-md-6">
              <x-input-field
                name="canonical_seo"
                label="Canonical URL"
                :value="old('canonical_seo')" />
            </div>

            <div class="col-12">
              <x-textarea-field
                name="description_seo"
                label="Description SEO"
                :value="old('description_seo')"
                rows="3" />
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
