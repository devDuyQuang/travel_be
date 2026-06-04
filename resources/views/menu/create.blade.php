@extends('index')

@section('title', 'Thêm ' . page_title())

@section('content')
<h5 class="card-header">Thêm {{ page_title() }}</h5>

<div class="card-body">
  <div id="ajax-alert" class="alert" role="alert" style="display:none"></div>

  <form action="{{ panel_route(module().'.store') }}" method="POST" id="menu-form">
    @csrf

    <x-input-field
      name="name"
      label="Tên menu"
      :value="old('name')"
      placeholder="Nhập tên menu" />

    <div class="mb-3">
      <label for="menu_topic" class="form-label">Chọn chủ đề</label>
      <select name="topic" id="menu_topic" class="form-select">
        <option value="">Chọn chủ đề</option>
        @foreach($types as $value => $label)
          <option value="{{ $value }}" {{ old('topic') == $value ? 'selected' : '' }}>
            {{ $label }}
          </option>
        @endforeach
      </select>
    </div>

    <div class="mb-3">
      <label for="part_id" class="form-label">Chọn đường dẫn</label>
      <select
        name="part_id"
        id="part_id"
        class="form-select">
        <option value="">Chọn đường dẫn</option>
      </select>
    </div>

    <x-select-field
      name="parent_id"
      label="Menu Cha"
      :options="$parents"
      :value="old('parent_id')"
      id="parent_id"
      placeholder="Chọn {{ page_title() }} Cha" />

    <x-select-field
      name="location"
      label="Vị trí (location)"
      :options="$locations"
      :value="old('location')"
      placeholder="Chọn vị trí" />

    <x-submit-buttons
      :cancel-route="panel_route(module().'.index')"
      submit-text="Lưu"
      cancel-text="Thoát" />
  </form>
</div>
@endsection

@push('scripts')
@include('partials.js.menu-create')
@endpush