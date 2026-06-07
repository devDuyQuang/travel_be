@extends('index')
@section('title', 'Chỉnh Sửa ' . page_title())

@section('content')
@include('partials.css.user')

<main class="main-wrapper user-form-page">
  <div class="main-content">
    <h5 class="user-form-title">
      Chỉnh Sửa {{ page_title() }}
    </h5>

    <div class="card-body user-form-card">
      <div id="ajax-alert" style="display:none" class="alert" role="alert"></div>

      <x-tab-form
        :action="panel_route(module().'.update', $item->id)"
        :index-url="panel_route(module().'.index')"
        method="PUT"
        :tabs="[
          ['id' => 'home', 'title' => 'Thông Tin Chung', 'active' => true]
        ]">

        <x-slot name="home">
          <x-input-field
            name="name"
            label="Họ & Tên"
            :required="true"
            :value="old('name', $item->name ?? '')" />

          <x-input-field
            name="email"
            type="email"
            label="Email"
            :required="true"
            :value="old('email', $item->email ?? '')" />

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
            <div class="invalid-feedback" id="error-role_id"></div>
          </div>

          <x-input-field
            name="password"
            type="password"
            label="Mật khẩu mới (tuỳ chọn)" />

          <x-input-field
            name="password_confirmation"
            type="password"
            label="Xác nhận mật khẩu mới" />
        </x-slot>

        <x-submit-buttons
          :cancel-route="panel_route(module().'.index')"
          submit-text="Cập nhật"
          cancel-text="Thoát" />
      </x-tab-form>
    </div>
  </div>
</main>
@endsection