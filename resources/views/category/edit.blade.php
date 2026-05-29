@extends('index')
@section('title', 'Chỉnh Sửa '. page_title())

@section('content')
  <h5 class="card-header">Chỉnh Sửa {{ page_title() }}</h5>

  <div class="card-body">
    <div id="ajax-alert" style="display:none" class="alert" role="alert"></div>

    <x-tab-form
      :action="panel_route(module().'.update', $item->id)"
      :index-url="panel_route(module().'.index')"
      method="PUT"
      :tabs="[
        ['id' => 'home', 'title' => 'Thông Tin Chung', 'active' => true],
        ['id' => 'seo',  'title' => 'Thẻ Seo']
      ]">

      <x-slot name="home">
        <x-input-field
          name="name" label="Tên" :required="true"
          :value="old('name', $item->name ?? '')" />

        <x-input-field
          name="slug" label="Slug" :required="true"
          :value="old('slug', $item->slug ?? '')" />

        <x-select-field
          name="parent_id"
          label="{{ page_title() }} Cha"
          :options="$parents"
          :value="old('parent_id', $item->parent_id ?? '')"
          id="parent_id"
          placeholder="Chọn {{ page_title() }} Cha"
        />

        <x-textarea-field
          name="description" label="Mô Tả" rows="3"
          :value="old('description', $item->description ?? '')" />

        <x-ckeditor
          name="content" label="Nội Dung"
          :value="old('content', $item->content ?? '')" />

        <x-file-input
          label="Ảnh Đại Diện"
          name="file"
          :multiple="false"
          :current-url="$currentImageUrl"
        />
        <x-file-input
         label="Icon"
         name="icon_file"
         :multiple="false"
         :current-url="$currentIconUrl"
         />
        <div class="mb-6">
          <label class="form-label" for="category-type">Loại</label>
          <select
            name="type"
            id="category-type"
            class="form-select @error('type') is-invalid @enderror no-select2"
          >
            <option value="Post" @selected(old('type', $item->type ?? 'Post') === 'Post')>Post</option>
            <option value="Service" @selected(old('type', $item->type ?? 'Post') === 'Service')>Service</option>
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
              :value="old('title_seo', $item->title_seo ?? '')"
            />
          </div>

          <div class="col-md-6">
            <x-input-field
              name="canonical_seo"
              label="Canonical URL"
              :value="old('canonical_seo', $item->canonical_seo ?? '')"
            />
          </div>

          <div class="col-12">
            <x-textarea-field
              name="description_seo"
              label="Description SEO"
              :value="old('description_seo', $item->description_seo ?? '')"
              rows="3"
            />
          </div>
        </div>
      </x-slot>

      <x-submit-buttons
        :cancel-route="panel_route(module().'.index')"
        submit-text="Cập nhật"
        cancel-text="Thoát" />

    </x-tab-form>
  </div>
@endsection
