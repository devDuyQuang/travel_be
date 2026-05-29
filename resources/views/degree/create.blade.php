@extends('index')
@section('title', 'Thêm mới Bằng cấp')

@section('content')
<h5 class="card-header">Thêm mới Bằng cấp</h5>
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
         <x-input-field name="name" label="Tên bằng cấp" :required="true" :value="old('name')" />
         
         <div class="mb-3">
             <label class="form-label">Hình ảnh</label>
             <input type="file" name="image" class="form-control" accept="image/*" />
         </div>

         <x-textarea-field name="description" label="Mô tả" rows="3" :value="old('description')" />
         <x-input-field name="link_text" label="Text liên kết" :value="old('link_text')" />

         <x-input-field name="year" label="Năm" :value="old('year')" />
      </x-slot>
      
      <x-submit-buttons :cancel-route="panel_route(module().'.index')" submit-text="Lưu lại" cancel-text="Hủy bỏ" />
   </x-tab-form>
</div>
@endsection
