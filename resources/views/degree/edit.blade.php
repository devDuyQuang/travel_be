@extends('index')
@section('title', 'Cập nhật Bằng cấp')

@section('content')
<h5 class="card-header">Cập nhật Bằng cấp: {{ $item->name }}</h5>
<div class="card-body">
   <div id="ajax-alert" style="display:none" class="alert" role="alert"></div>
   <x-tab-form
      :action="panel_route(module().'.update', $item->id)"
      :index-url="panel_route(module().'.index')"
      method="PUT"
      :tabs="[
          ['id' => 'home', 'title' => 'Thông Tin Chung', 'active' => true]
      ]">
      <x-slot name="home">
         <x-input-field name="name" label="Tên bằng cấp" :required="true" :value="$item->name" />
         
         <div class="mb-3">
             <label class="form-label">Hình ảnh</label>
             @if($item->image)
                <div class="mb-2">
                    <img src="{{ Storage::url($item->image) }}" width="100" class="rounded" alt="Current Image">
                </div>
             @endif
             <input type="file" name="image" class="form-control" accept="image/*" />
             <div class="form-text">Để trống nếu không thay đổi hình ảnh.</div>
         </div>

         <x-textarea-field name="description" label="Mô tả" rows="3" :value="$item->description" />
         <x-input-field name="link_text" label="Text liên kết" :value="old('link_text', $item->link_text)" />

<x-input-field name="year" label="Năm" :value="old('year', $item->year)" />
      </x-slot>
      
      <x-submit-buttons :cancel-route="panel_route(module().'.index')" submit-text="Cập nhật" cancel-text="Hủy bỏ" />
   </x-tab-form>
</div>
@endsection
