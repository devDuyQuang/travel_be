@extends('index')
@section('title', 'Thêm mới Bác sĩ')

@section('content')
@include('partials.css.doctor')

<main class="main-wrapper doctor-form-page">
  <div class="main-content">
    <h5 class="card-header doctor-form-title">
      Thêm mới Nhân Viên
    </h5>

    <div class="card-body doctor-form-card">
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
            label="Tên nhân viên"
            :required="true"
            :value="old('name')"
          />

          <x-input-field
            name="specialty"
            label="Chuyên ngành"
            :value="old('specialty')"
          />

          <x-input-field
            name="birth_year"
            label="Năm sinh"
            type="number"
            :value="old('birth_year')"
          />

          <x-input-field
            name="phone"
            label="Số điện thoại"
            :value="old('phone')"
          />

          <x-input-field
            name="linkedin"
            label="LinkedIn"
            :value="old('linkedin')"
          />

          <x-input-field
            name="facebook"
            label="Facebook"
            :value="old('facebook')"
          />

          <x-input-field
            name="twitter"
            label="Twitter"
            :value="old('twitter')"
          />

          <x-input-field
            name="youtube"
            label="YouTube"
            :value="old('youtube')"
          />

          <x-file-input
            label="Ảnh đại diện"
            name="image"
            :multiple="false"
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