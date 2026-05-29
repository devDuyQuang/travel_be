@extends('index')
@section('title', 'Chỉnh Sửa ' . page_title())

@section('content')
<h5 class="card-header">Chỉnh Sửa {{ page_title() }}</h5>
<div class="card-body">
  <div id="ajax-alert" style="display:none" class="alert" role="alert"></div>

  <x-tab-form
    :action="panel_route(module() . '.update', $item->id)"
    :index-url="panel_route(module() . '.index')"
    method="PUT"
    :tabs="[
      ['id' => 'home', 'title' => 'Thông Tin Chung', 'active' => true]
    ]">
    <x-slot name="home">
      <x-input-field name="name" label="Domain" :required="true" :value="old('name', $item->name)" />

      <div class="mb-6">
        <label class="form-label" for="type">Type <span class="text-danger">*</span></label>
        <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
          <option value="clinic" @selected(old('type', $item->type) === 'clinic')>clinic</option>
          <option value="RAC" @selected(old('type', $item->type) === 'RAC')>RAC</option>
        </select>
        <div class="invalid-feedback" id="error-type">
          @error('type') {{ $message }} @enderror
        </div>
      </div>
    </x-slot>

    <x-submit-buttons :cancel-route="panel_route(module() . '.index')" submit-text="Cập nhật" cancel-text="Thoát" />
  </x-tab-form>
</div>
@endsection
