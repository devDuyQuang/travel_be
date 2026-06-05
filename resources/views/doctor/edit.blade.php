@extends('index')
@section('title', 'Cập nhật Bác sĩ')

@section('content')
@include('partials.css.doctor')

<main class="main-wrapper doctor-form-page">
  <div class="main-content">
    <h5 class="card-header doctor-form-title">
      Cập nhật Bác sĩ: {{ $item->name }}
    </h5>

    <div class="card-body doctor-form-card">
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
            label="Tên bác sĩ"
            :required="true"
            :value="old('name', $item->name)"
          />

          <x-input-field
            name="specialty"
            label="Chuyên khoa"
            :value="old('specialty', $item->specialty)"
          />

          <x-input-field
            name="birth_year"
            label="Năm sinh"
            type="number"
            :value="old('birth_year', $item->birth_year)"
          />

          <x-input-field
            name="phone"
            label="Số điện thoại"
            :value="old('phone', $item->phone)"
          />

          <x-input-field
            name="linkedin"
            label="LinkedIn"
            :value="old('linkedin', $item->linkedin)"
          />

          <x-input-field
            name="facebook"
            label="Facebook"
            :value="old('facebook', $item->facebook)"
          />

          <x-input-field
            name="twitter"
            label="Twitter"
            :value="old('twitter', $item->twitter)"
          />

          <x-input-field
            name="youtube"
            label="YouTube"
            :value="old('youtube', $item->youtube)"
          />

          <x-file-input
            label="Ảnh đại diện"
            name="image"
            :multiple="false"
            :current-url="$currentImageUrl"
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