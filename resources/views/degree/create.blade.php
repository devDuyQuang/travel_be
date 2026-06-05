@extends('index')
@section('title', 'Thêm mới Bằng cấp')

@section('content')
@include('partials.css.degree')

<main class="main-wrapper degree-form-page">
  <div class="main-content">
    <h5 class="card-header degree-form-title">
      Thêm mới Bằng cấp
    </h5>

    <div class="card-body degree-form-card">
      <div id="ajax-alert" style="display:none" class="alert" role="alert"></div>

      <x-tab-form
        :action="panel_route(module().'.store')"
        :index-url="panel_route(module().'.index')"
        method="POST"
        enctype="multipart/form-data"
        :tabs="[
          ['id' => 'home', 'title' => 'Thông Tin Chung', 'active' => true]
        ]"
      >
        <x-slot name="home">
          <x-input-field
            name="name"
            label="Tên bằng cấp"
            :required="true"
            :value="old('name')"
          />

          <x-file-input
            label="Hình ảnh"
            name="image"
            :multiple="false"
          />

          <x-textarea-field
            name="description"
            label="Mô tả"
            rows="3"
            :value="old('description')"
          />

          <x-input-field
            name="link_text"
            label="Text liên kết"
            :value="old('link_text')"
          />

          <x-input-field
            name="year"
            label="Năm"
            :value="old('year')"
          />
        </x-slot>

        <x-submit-buttons
          :cancel-route="panel_route(module().'.index')"
          submit-text="Lưu lại"
          cancel-text="Hủy bỏ"
        />
      </x-tab-form>
    </div>
  </div>
</main>
@endsection