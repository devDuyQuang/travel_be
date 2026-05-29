@extends('index')
@section('title', 'Thêm mới Bác sĩ')

@section('content')
<h5 class="card-header">Thêm mới Bác sĩ</h5>
<div class="card-body">
   <div id="ajax-alert" style="display:none" class="alert" role="alert"></div>
   <x-tab-form
      :action="panel_route(module().'.store')"
      :index-url="panel_route(module().'.index')"
      method="POST"
      :tabs="[
          ['id' => 'home', 'title' => 'Thông Tin Chung', 'active' => true]
      ]">
      <x-slot name="home">
         <x-input-field name="name" label="Tên bác sĩ" :required="true" :value="old('name')" />
         <x-input-field name="specialty" label="Chuyên khoa" :value="old('specialty')" />
         <x-input-field name="birth_year" label="Năm sinh" type="number" :value="old('birth_year')" />
         <x-input-field name="phone" label="Số điện thoại" :value="old('phone')" />
         <x-input-field name="linkedin" label="LinkedIn" :value="old('linkedin')" />
         <x-input-field name="facebook" label="Facebook" :value="old('facebook')" />
         <x-input-field name="twitter" label="Twitter" :value="old('twitter')" />
         <x-input-field name="youtube" label="YouTube" :value="old('youtube')" />
         <x-file-input label="Ảnh đại diện" name="image" :multiple="false" />
      </x-slot>

      <x-submit-buttons :cancel-route="panel_route(module().'.index')" submit-text="Lưu lại" cancel-text="Hủy bỏ" />
   </x-tab-form>
</div>
@endsection
