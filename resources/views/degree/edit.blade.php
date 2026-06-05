@extends('index')
@section('title', 'Cập nhật Bằng cấp')

@section('content')
@include('partials.css.degree')

<main class="main-wrapper degree-form-page">
  <div class="main-content">
    <h5 class="card-header degree-form-title">
      Cập nhật Bằng cấp: {{ $item->name }}
    </h5>

    <div class="card-body degree-form-card">
      <div id="ajax-alert" style="display:none" class="alert" role="alert"></div>

      <x-tab-form
        :action="panel_route(module().'.update', $item->id)"
        :index-url="panel_route(module().'.index')"
        method="PUT"
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
            :value="old('name', $item->name)"
          />

          <x-file-input
            label="Hình ảnh"
            name="image"
            :multiple="false"
            :current-url="$currentImageUrl"
          />

          <x-textarea-field
            name="description"
            label="Mô tả"
            rows="3"
            :value="old('description', $item->description)"
          />

          <x-input-field
            name="link_text"
            label="Text liên kết"
            :value="old('link_text', $item->link_text)"
          />

          <x-input-field
            name="year"
            label="Năm"
            :value="old('year', $item->year)"
          />
        </x-slot>

        <x-submit-buttons
          :cancel-route="panel_route(module().'.index')"
          submit-text="Cập nhật"
          cancel-text="Hủy bỏ"
        />
      </x-tab-form>
    </div>
  </div>
</main>
@endsection