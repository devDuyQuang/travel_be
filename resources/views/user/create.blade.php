@extends('index')
@section('title', 'Thêm ' . page_title())

@section('content')
<h5 class="card-header">Thêm {{ page_title() }}</h5>

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
      <x-input-field
        name="name"
        label="Họ & Tên"
        :required="true"
        :value="old('name')" />

      <x-input-field
        name="email"
        type="email"
        label="Email"
        :required="true"
        :value="old('email')" />
      <div class="mb-3">
        <label class="form-label">Role</label>
        <select name="role_id" class="form-control">
          <option value="">Chọn quyền</option>
          @foreach($roles as $role)
          <option value="{{ $role->id }}" @selected(old('role_id', $item->role_id ?? null) == $role->id)>
            {{ $role->name }}
          </option>
          @endforeach
        </select>
      </div>

      <x-input-field
        name="password"
        type="password"
        label="Mật khẩu"
        :required="true" />

      {{-- Nếu bạn thêm rule `confirmed` trong controller, bật field này --}}
      <x-input-field
        name="password_confirmation"
        type="password"
        label="Xác nhận mật khẩu" />
    </x-slot>

    <x-submit-buttons
      :cancel-route="panel_route(module().'.index')"
      submit-text="Lưu"
      cancel-text="Thoát" />
  </x-tab-form>
</div>
@endsection