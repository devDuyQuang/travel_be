@extends('index')
@section('title', 'Thêm '. page_title())
@section('content')
<h5 class="card-header">Thêm {{ page_title() }}</h5>
<div class="card-body">
   <div id="ajax-alert" style="display:none" class="alert" role="alert"></div>
   <x-tab-form
      :action="panel_route(module().'.store')"
      :index-url="panel_route(module().'.index')"
      method="POST"
      :tabs="[
      ['id' => 'home', 'title' => 'Thông Tin Chung', 'active' => true],
      ['id' => 'seo',  'title' => 'Thẻ Seo']
      ]">
      <x-slot name="home" class="mt-2">
         <x-input-field name="name" label="Tên" :required="true" :value="old('name')" />
         <x-input-field name="slug" label="Slug" :required="true" :value="old('slug')" />
         <div class="mb-6">
            <label class="form-label">Danh mục</label>
            <select name="category_ids[]" id="post-category_ids" class="select2 form-select" multiple>
               @foreach($categories ?? [] as $id => $name)
                  <option value="{{ $id }}" @selected(in_array($id, old('category_ids', [])))>{{ $name }}</option>
               @endforeach
            </select>
         </div>
         <x-textarea-field name="description" label="Mô Tả" rows="3" :value="old('description')" />
         <x-ckeditor name="content" label="Nội Dung" :value="$post->content ?? ''" />
         <x-file-input label="Ảnh Đại Diện" name="file" :multiple="false" />
      </x-slot>
      <x-slot name="seo">
        <div class="row g-3">
          <div class="col-md-6">
            <x-input-field
              name="title_seo"
              label="Title SEO"
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
              label="Description SEO"
              :value="old('description_seo')"
              rows="3"
            />
          </div>
        </div>
      </x-slot>
      {{-- <x-checkbox-field name="status" label="Hiển thị" :checked="old('status', 1)" /> --}}
      <x-submit-buttons :cancel-route="panel_route(module().'.index')" submit-text="Lưu" cancel-text="Thoát" />
   </x-tab-form>
</div>
@endsection
